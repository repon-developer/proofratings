const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const Schema = () => {
    const [settings, setSettings] = useState(store.getState().settings)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings(store.getState().settings))
        return () => unsubscribe();
    }, [])

    let schema_markup = `{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Proofratings",
    "image": "https://proofratings.com/wp-content/uploads/2021/08/Proofratings-site-header-logo.svg",
    "url": "https://proofratings.com/",
    "telephone": "(833) 662-0706",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "202 N. Dixon Ave.",
        "addressLocality": "Cary",
        "addressRegion": "NY",
        "postalCode": "27513",
        "addressCountry": "US"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": {{ratingValue}},
        "bestRating": "5",
        "ratingCount": {{ratingCount}}
    }
}`

    if (settings?.schema) {
        schema_markup = settings?.schema;
    }

    const enable_shema = settings?.enable_shema;

    return (
        <React.Fragment>
            <h2 className="section-title-large" style={{ marginBottom: 20 }}>Structured data</h2>
            <div className="schema-wrapper">
                <div className='left-column'>
                    <label className="label-switch-checkbox" style={{ marginBottom: 15 }}>
                        <input className="checkbox-switch" type="checkbox" onChange={(e) => store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { enable_shema: !enable_shema } })} defaultChecked={enable_shema} />
                        <span>Disable Schema Markup</span>
                        <span>Enable Schema Markup</span>
                    </label>
                    <textarea defaultValue={schema_markup} onInput={(e) => store.dispatch({ type: ACTIONS.UPDATE_SCHEMA, payload: e.target.value })}></textarea>
                    <p className="description">Add the script block below to the head section of your html.</p>
                </div>

                <div>
                    <div style={{ marginBottom: 33 }} />
                    <div className="intro-text">
                        <h3>Add star ratings to search results</h3>
                        <p>Now that you display your rating badges on your website, you're able to gain your overall rating in search results.</p>
                    </div>
                </div>
            </div>
        </React.Fragment>
    );
};

export default Schema;
