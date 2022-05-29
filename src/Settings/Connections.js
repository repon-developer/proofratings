const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const SiteConnections = ({location_id}) => {
    const [search, setSearch] = useState('')

    const [state, setState] = useState(store.getState().settings)

    const active_connections = (typeof state?.active_connections === 'object') ? state.active_connections : {};
    const connections_approved = Array.isArray(proofratings?.connections_approved) ? proofratings.connections_approved : [];

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().settings))
        return () => unsubscribe();
    }, [])

    const isUrl = string => {
        try { return Boolean(new URL(string)); }
        catch (e) { return false; }
    }

    const is_overall = location_id === 'overall';

    const handle_connections = (slug, key, value) => {
        if (is_overall) { return }

        const connection = (typeof active_connections[slug] === 'object') ? active_connections[slug] : {};
        active_connections[slug] = Object.assign(connection, { [key]: value });

        store.dispatch({ type: ACTIONS.UPDATE_CONNECTIONS, payload: active_connections });
    }

    const handle_check_connection = (slug) => {
        const connection = (typeof active_connections[slug] === 'object') ? active_connections[slug] : {};
        const selected = typeof connection.selected === 'undefined' || connection.selected === false ? true : false;
        handle_connections(slug, 'selected', selected)
    }

    const review_sites = [];
    if (typeof proofratings?.review_sites === 'object') {
        Object.entries(proofratings.review_sites).forEach((item) => {
            item[1].slug = item[0];
            item[1].approved = connections_approved.includes(item[0])
            review_sites.push(item[1])
        })
    }

    const get_row = (item) => {
        const slug = item.slug;
        const current_connection = (typeof active_connections[slug] === 'object') ? active_connections[slug] : {};
        const review_site = Object.assign(item, { selected: false, url: '' }, current_connection);

        const pending_items = () => <td className="message-pending-connections" colSpan={4}>We are working on you connection and notify you when complete.</td>

        if ( is_overall ) {
            review_site.click_through_url = '';
        }

        const default_items = () => {
            if (review_site.approved === true) {
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
                <td><input className="checkbox-switch checkbox-onoff" type="checkbox" defaultChecked={review_site.selected} onClick={() => handle_check_connection(slug)} /></td>
                <td className="review-site-logo"><img src={review_site.logo} alt={review_site.name} /></td>

                {item.selected === true && item.approved === false ? pending_items() : default_items()}
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
    })//.sort((a, b) => b.selected - a.selected)

    return (
        <React.Fragment>

            <div className="search-review-sites-wrapper">
                <div className="left-column">
                    <form className="form-search-review-sites" style={{ alignSelf: 'flex-end' }} onSubmit={(e) => e.preventDefault()}>
                        <input type="text" placeholder="Search..." onChange={(e) => setSearch(e.target.value)} />
                        <button></button>
                    </form>
                </div>

                <div className="intro-text">
                    <h3>Connect review sites</h3>

                    {(is_overall === true) &&
                        <>
                            <p>When all locations is selected, you will not see site connections available.</p>
                            <p>All locations is a collective of
                                all your locations and the sites available will be the ones connected with each individual site. In order to
                                access and edit connected sites, select individual locations.
                            </p>
                        </>
                    }

                    {(is_overall === false) &&
                        <div>
                            <p>Below is the current list of supported review sites to connect for your rating badges. During the initial setup of your account, we will connect the review sites your requested at sign up.</p>
                            <p>If you would like to add additional sites, simply toggle on the review site and our support team will be notified to make the connection on the backend.</p>
                            <p>Once connected, you can include the new review site in badges by accessing your Rating Badges tab.</p>
                            <p>You can edit the click-through URL in this area if you would like your website visitors to visit a different link when they click the pertaining badge.</p>
                        </div>
                    }
                </div>
            </div>

            <div className="gap-50" />

            <table className={`table-review-sites ${is_overall ? 'table-location-overall' : ''}`} >
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
