
const get_proofrating = () => proofratings

const copy_shortcode = (attrs, event) => {
    if ( event.target ) {
        event.preventDefault();
    }

    const shorcode = Object.assign({slug: 'proofratings_widgets', id: '', style: ''}, attrs);

    let shortcode_text = '[' + shorcode.slug;
    
    if ( shorcode.style.length ) {
        shortcode_text += ` style="${shorcode.style}"`
    }

    if ( !Boolean(get_proofrating()?.global) ) {
        shortcode_text += ` id="${shorcode?.id}"`;
    }

    shortcode_text += ']';

    navigator.clipboard.writeText(shortcode_text)
}

export {get_proofrating, copy_shortcode };
