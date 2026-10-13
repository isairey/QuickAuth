import type { App, InjectionKey } from 'vue';
import { defineComponent, h, inject, provide } from 'vue';

export type QuickAuthProvider = 'google' | 'facebook';

export interface QuickAuthOptions {
  /** Backend origin that implements the QuickAuth HTTP endpoints. */
  baseUrl: string;
  providers: QuickAuthProvider[];
}

export interface QuickAuthClient {
  begin(provider: QuickAuthProvider): void;
}

const QuickAuthKey: InjectionKey<QuickAuthClient> = Symbol('QuickAuth');

export function createQuickAuth(options: QuickAuthOptions) {
  const allowed = new Set(options.providers);

  const client: QuickAuthClient = {
    begin(provider) {
      if (!allowed.has(provider)) {
        throw new Error(`QuickAuth provider "${provider}" is not enabled.`);
      }

      // Navigation targets the backend; OAuth secrets must never live in the browser.
      const base = options.baseUrl.replace(/\/+$/, '');
      window.location.assign(`${base}/auth/${encodeURIComponent(provider)}`);
    },
  };

  return {
    install(app: App) {
      provide(QuickAuthKey, client);
      app.provide(QuickAuthKey, client);
      app.component('QuickAuthButton', defineComponent({
        name: 'QuickAuthButton',
        props: {
          provider: {
            type: String,
            required: true,
            validator: (value: string) => value === 'google' || value === 'facebook',
          },
          label: { type: String, required: false },
        },
        setup(props) {
          const auth = inject(QuickAuthKey);
          return () => h('button', {
            type: 'button',
            onClick: () => {
              if (!auth) throw new Error('QuickAuth plugin is not installed.');
              auth.begin(props.provider as QuickAuthProvider);
            },
          }, props.label ?? `Continue with ${props.provider}`);
        },
      }));
    },
  };
}
