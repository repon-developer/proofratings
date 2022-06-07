
const get_proofratings = () => proofratings

const copy_shortcode = (attrs, event) => {
    if (event.target) {
        event.preventDefault();
    }

    const shorcode = Object.assign({ slug: 'proofratings_widgets', id: '', style: '' }, attrs);

    let shortcode_text = '[' + shorcode.slug;

    if (shorcode.style.length) {
        shortcode_text += ` style="${shorcode.style}"`
    }

    if (!Boolean(get_proofratings()?.global)) {
        shortcode_text += ` id="${shorcode?.id}"`;
    }

    shortcode_text += ']';

    navigator.clipboard.writeText(shortcode_text)

    const toast = document.getElementById('toast-proofratings')
    toast.textContent = 'Shortcode has been copied';
    setTimeout(() => toast.textContent = '', 800)
}

export { get_proofratings, copy_shortcode };
