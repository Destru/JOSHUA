function switchStyle(styleName) {
	$('link[@rel*=style][title]').each(function(i) {
		this.disabled = true;
		if (this.getAttribute('title') == styleName) this.disabled = false;
	});
	createCookie('theme', styleName, 365);
}