const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const Schema = () => {
    const [settings, setSettings] = useState(store.getState())

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings(store.getState()))
        return () => unsubscribe();
    }, [])

    console.log( settings );


    return (
        <React.Fragment>
            <h2 className="section-title-large" style={{ marginBottom: 20 }}>Structured data</h2>
            <div className="schema-wrapper">
                <div className='left-column'>
                    <textarea></textarea>
                    <p className="description">Add the script block below to the head section of your html.</p>
                </div>

                <div className="intro-text">
                    <h3>Add star ratings to search results</h3>
                    <p>Now that you display your rating badges on your website, you're able to gain your overall rating in search results.</p>
                </div>
            </div>
        </React.Fragment>
    );
};

export default Schema;
