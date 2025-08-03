const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editor;
const { PanelBody, Button, Dropdown } = wp.components;
const { useState, createElement, Fragment } = wp.element;

const LanguageSwitcher = () => {
  const [language, setLanguage] = useState(acfaiData.defaultLanguageCode || '');
  const languages = acfaiData.languages;

  const selectedLabel = language ? languages[language]?.name : '';

  return createElement(
      Fragment,
      null,
      createElement(
          PluginSidebarMoreMenuItem,
          { target: 'acfai-language-sidebar' },
          'Select Language'
      ),
      createElement(
          PluginSidebar,
          { name: 'acfai-language-sidebar', title: 'Select language' },
          createElement(
              PanelBody,
              null,
              createElement(Dropdown, {
                contentClassName: 'acfai-language-dropdown',
                popoverProps: { placement: 'bottom-start' },
                renderToggle: ({ isOpen, onToggle }) =>
                    createElement(
                        Button,
                        {
                          variant: 'secondary',
                          onClick: onToggle,
                          'aria-expanded': isOpen,
                        },
                        language && languages[language]?.flag_url
                            ? createElement('img', {
                              src: languages[language].flag_url,
                              alt: language,
                              style: {
                                width: 24,
                                height: 16,
                                marginRight: 8,
                                verticalAlign: 'middle',
                              },
                            })
                            : null,
                        selectedLabel
                    ),
                renderContent: () =>
                    createElement(
                        'div',
                        { style: { maxHeight: '200px', overflowY: 'auto' } },
                        Object.entries(languages).map(([code, data]) =>
                            createElement(
                                Button,
                                {
                                  key: code,
                                  isSecondary: true,
                                  isPressed: language === code,
                                  onClick: () => setLanguage(code),
                                  style: {
                                    display: 'flex',
                                    alignItems: 'center',
                                    width: '100%',
                                    textAlign: 'left',
                                    backgroundColor: language === code ? 'rgb(0, 107, 161)' : undefined,
                                    borderLeft: language === code ? '4px solid #007c91' : undefined,
                                  },
                                },
                                data.flag_url
                                    ? createElement('img', {
                                      src: data.flag_url,
                                      alt: code,
                                      style: { width: 24, height: 16, marginRight: 8 },
                                    })
                                    : null,
                                data.name
                            )
                        )
                    ),
              })
          )
      )
  );
};

registerPlugin('acfai-language-switcher', {
  render: LanguageSwitcher,
  icon: 'translation',
});