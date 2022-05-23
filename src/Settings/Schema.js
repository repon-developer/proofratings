const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const Schema = () => {
    const [search, setSearch] = useState('')
    const [state, setState] = useState(store.getState())

    const connections = (typeof state?.connections === 'object') ? state.connections : {};
    const connection_approved = Array.isArray(state?.connection_approved) ? state.connection_approved : [];

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState()))
        return () => unsubscribe();
    }, [])

    const isUrl = string => {
        try { return Boolean(new URL(string)); }
        catch (e) { return false; }
    }

    const handle_connections = (slug, key, value) => {
        const connection = (typeof connections[slug] === 'object') ? connections[slug] : {};
        connections[slug] = Object.assign(connection, { [key]: value });

        store.dispatch({ type: ACTIONS.UPDATE_CONNECTIONS, payload: connections });
    }

    const handle_check_connection = (slug) => {
        const connection = (typeof connections[slug] === 'object') ? connections[slug] : {};
        const active = typeof connection.active === 'undefined' || connection.active === false ? true : false;
        handle_connections(slug, 'active', active)
    }

    const review_sites = [];
    if (typeof proofratings?.review_sites === 'object') {
        Object.entries(proofratings.review_sites).forEach((item) => {
            item[1].slug = item[0];
            item[1].approved = connection_approved.includes(item[0])
            review_sites.push(item[1])
        })
    }

    const get_row = (item) => {
        const slug = item.slug, current_connection = (typeof connections[slug] === 'object') ? connections[slug] : {};
        const review_site = Object.assign(item, { selected: false, url: '' }, current_connection);

        const pending_items = () => <td className="message-pending-connections" colSpan={4}>We are working on you connection and notify you when complete.</td>

        const default_items = () => {
            if (item.active === true && item.approved === true) {
                return (
                    <>
                        <td className="bold">55</td>
                        <td className="bold">4</td>
                        <td className="click-through-url">
                            <input type="text" defaultValue={review_site.click_through_url} onInput={(e) => handle_connections(slug, 'click_through_url', e.target.value)} />
                            {isUrl(review_site.click_through_url) ? <a className="fa-solid fa-up-right-from-square" href={review_site.click_through_url} target="_blank"></a> : ''}
                        </td>
                    </>
                )
            }

            return <td colSpan={4}></td>
        }

        return (
            <React.Fragment>
                <td><input className="checkbox-switch checkbox-onoff" type="checkbox" defaultChecked={review_site.active} onClick={() => handle_check_connection(slug)} /></td>
                <td className="review-site-logo"><img src={review_site.logo} alt={review_site.name} /></td>

                {item.active === true && item.approved === false ? pending_items() : default_items()}
            </React.Fragment>
        )
    }

    const review_sites_filtered = review_sites.filter(item => {
        if (!search.length) {
            return true;
        }

        if (typeof item.name === 'undefined') {
            return false;
        }

        return item.name.toLowerCase().match(new RegExp(search));
    });


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
