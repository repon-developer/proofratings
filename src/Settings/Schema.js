const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const Schema = () => {
    const [settings, setSettings] = useState(store.getState().settings)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings(store.getState().settings))
        return () => unsubscribe();
    }, [])

    const enable_schema = settings?.enable_schema;

    return (
        <React.Fragment>
            <h2 className="section-title-large" style={{ marginBottom: 20 }}>Structured data</h2>
            <div className="schema-wrapper">
                <div className='left-column'>
                    <label className="label-switch-checkbox" style={{ marginBottom: 15 }}>
                        <input className="checkbox-switch" type="checkbox" onChange={(e) => store.dispatch({ type: ACTIONS.ENABLE_SCHEMA, payload: !enable_schema })} defaultChecked={enable_schema} />
                        <span>Disable Schema Markup</span>
                        <span>Enable Schema Markup</span>
                    </label>
                    <textarea defaultValue={settings?.schema} onInput={(e) => store.dispatch({ type: ACTIONS.UPDATE_SCHEMA, payload: e.target.value })}></textarea>
                    <p className="description">DO NOT edit this data unless you know what you are doing.</p>
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
