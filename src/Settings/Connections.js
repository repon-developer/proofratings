const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const SiteConnections = () => {
    const [search, setSearch] = useState('')
    const [state, setState] = useState(store.getState())
    const connections = (typeof state?.connections === 'object') ? state.connections : {};
    
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
        connections[slug] = Object.assign(connection, {[key]: value});
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
            review_sites.push(item[1])
        })
    }

    const get_row = (item) => {
        const slug = item.slug, current_connection = (typeof connections[slug] === 'object') ? connections[slug] : {};
        const review_site = Object.assign(item, {selected: false, url: ''}, current_connection);

        return (
            <React.Fragment>
                <td><input className="checkbox-switch checkbox-onoff" type="checkbox" defaultChecked={review_site.active} onClick={() => handle_check_connection(slug)} /></td>
                <td className="review-site-logo"><img src={review_site.logo} alt={review_site.name} /></td>
                <td className="bold">55</td>
                <td className="bold">4</td>
                <td className="click-through-url">
                    <input type="text" defaultValue={review_site.url} onInput={(e) => handle_connections(slug, 'url', e.target.value )} />
                    {isUrl(review_site.url) ? <a className="fa-solid fa-up-right-from-square" href={review_site.url} target="_blank"></a> : ''}
                </td>
            </React.Fragment>
        )
    }

    const review_sites_filtered = review_sites.filter(item => {
        if ( !search.length ) {
            return true;
        }

        if ( typeof item.name === 'undefined' ) {
            return false;
        }

        return item.name.toLowerCase().match(new RegExp(search));
    });

    return (
        <React.Fragment>

            <div className="search-review-sites-wrapper">
                <form className="form-search-review-sites" style={{ alignSelf: 'flex-end' }}>
                    <input type="text" placeholder="Search..." onChange={(e) => setSearch(e.target.value)} />
                    <button></button>
                </form>

                <div className="intro-text">
                    <h3>Connect review sites</h3>
                    <p>Below is the current list of supported review sites to connect for your rating badges. During the initial setup of your account, we will connect the review sites your requested at sign up.</p>
                    <p>If you would like to add additional sites, simply toggle on the review site and our support team will be notified to make the connection on the backend.</p>
                    <p>Once connected, you can include the new review site in badges by accessing your Rating Badges tab.</p>
                    <p>You can edit the click-through URL in this area if you would like your website visitors to visit a different link when they click the pertaining badge.</p>
                </div>
            </div>

            <div className="gap-50" />

            <table className="table-review-sites">
                <thead>
                    <tr>
                        <th></th>
                        <th className="column-review-sites">Review Site</th>
                        <th>Rating</th>
                        <th># of Reviews</th>
                        <th className="column-click-through-url">Click-through URL</th>
                    </tr>
                </thead>

                <tbody>
                    {review_sites_filtered.map((review_site) => <tr key={review_site.slug}>{get_row(review_site)}</tr>)}
                </tbody>
            </table>



        </React.Fragment>
    );
};

export default SiteConnections;
