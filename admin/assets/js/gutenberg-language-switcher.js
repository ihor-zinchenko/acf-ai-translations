const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editor;
const { PanelBody, Button, Dropdown } = wp.components;
const { withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;
const { useState, useEffect, createElement, Fragment } = wp.element;

const LanguageSwitcher = ({ language, setLanguage }) => {
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

const LanguageContentController = compose([
  withSelect((select) => {
    const originalMeta = select('core/editor').getCurrentPostAttribute('meta') || {};
    const originalTitle = select('core/editor').getCurrentPostAttribute('title') || '';
    const originalContent = select('core/editor').getCurrentPostAttribute('content') || '';

    const editedMeta = select('core/editor').getEditedPostAttribute('meta') || {};
    const editedTitle = select('core/editor').getEditedPostAttribute('title') || '';
    const editedContent = select('core/editor').getEditedPostAttribute('content') || '';

    console.log('original:', originalTitle, originalMeta, originalContent);
    console.log('edited:', editedTitle, editedMeta, editedContent);

    return {
      originalMeta,
      originalTitle,
      originalContent,
      meta: editedMeta,
      title: editedTitle,
      content: editedContent,
    };
  }),
  withDispatch((dispatch, ownProps) => {
    return {
      setTitle: (title) => {
        dispatch('core/editor').editPost({ title });
      },
      setContent: (content) => {
        dispatch('core/editor').editPost({ content });
      },
      setMeta: (meta) => {
        dispatch('core/editor').editPost({ meta });
      },
    };
  }),
])(({ meta, title, content, setTitle, setContent, setMeta, originalMeta, originalTitle, originalContent }) => {
  const [language, setLanguage] = useState(acfaiData.defaultLanguageCode || 'en');
  const [translations, setTranslations] = useState({});

  useEffect(() => {
    if (Object.keys(translations).length === 0) {
      setTitle(originalTitle);
      setContent(originalContent);
      setMeta(originalMeta);
    } else if (translations[language]) {
      setTitle(translations[language].title || '');
      setContent(translations[language].content || '');
      setMeta(translations[language].meta || {});
    } else {
      setTitle('');
      setContent('');
      setMeta({});
    }
  }, [language, translations, originalTitle, originalContent, originalMeta]);

  useEffect(() => {
    setTranslations(prev => {
      const prevLangData = prev[language] || {};

      if (
          prevLangData.title !== title ||
          prevLangData.content !== content ||
          JSON.stringify(prevLangData.meta) !== JSON.stringify(meta)
      ) {
        return {
          ...prev,
          [language]: {
            title,
            content,
            meta,
          }
        };
      }
      return prev; // Якщо нічого не змінилося — повертаємо старий стейт без змін
    });
  }, [title, content, meta, language]);

  return createElement(
      Fragment,
      null,
      createElement(LanguageSwitcher, { language, setLanguage })
  );
});

registerPlugin('acfai-language-content-controller', {
  render: LanguageContentController,
  icon: 'translation',
});