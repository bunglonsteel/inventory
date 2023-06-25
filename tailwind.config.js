// /** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		"./application/views/layouts/front/**/*.{html,js,php}",
		"./application/views/pages/*.{html,js,php}",
		"./application/controllers/Pages.php",
	],
	theme: {
		fontFamily: {
			inter: ["Inter", "sans-serif"],
			sans_pro: ["Source sans pro", "serif"],
		},
	},
	plugins: [],
};
