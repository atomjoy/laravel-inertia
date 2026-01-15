import messages from './messages.js';

const lang = {
	locale: 'pl', // set locale
	fallbackLocale: 'pl', // set fallback locale
	availableLocales: ['en', 'pl'], // available locales
	messages: messages,
	allowComposition: true, // Allow compositions api
	globalInjection: true, // Allow t compositions api
	legacy: false, // Allow t compositions api
};

export default lang;
