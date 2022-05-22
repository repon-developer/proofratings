const { useState, useEffect } = React;

const SiteConnections = (props) => {
    const [state, setState] = useState({
        search: ''
    })

    useEffect(() => {

    }, [])

    const isUrl = string => {
        try { return Boolean(new URL(string)); }
        catch (e) { return false; }
    }

    const review_sites = [];
    if (typeof proofratings?.review_sites === 'object') {
        Object.entries(proofratings.review_sites).forEach((item) => {
            item[1].slug = item[0];
            review_sites.push(item[1])
        })
    }

    const get_row = (review_site) => {
        return (
            <React.Fragment>
                <td><input className="checkbox-switch checkbox-onoff" type="checkbox" /></td>
                <td className="review-site-logo"><img src={review_site.logo} alt={review_site.name} /></td>
                <td className="bold">55</td>
                <td className="bold">4</td>
                <td className="click-through-url">
                    <input type="text" defaultValue="" />
                    {isUrl('https://thispointer.com/javascript-check-if-string-is-url/') ? <a className="fa-solid fa-up-right-from-square" href="sfsf" target="_blank"></a> : ''}
                </td>
            </React.Fragment>
        )
    }

    const review_sites_filtered = review_sites.filter(item => {
        if ( !state.search.length ) {
            return true;
        }

        if ( typeof item.name === 'undefined' ) {
            return false;
        }

        return item.name.toLowerCase().match(new RegExp(state.search));
    });

    return (
        <React.Fragment>

            <div className="search-review-sites-wrapper">
                <form className="form-search-review-sites" style={{ alignSelf: 'flex-end' }}>
                    <input type="text" placeholder="Search..." onChange={(e) => setState({ ...state, search: e.target.value })} />
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
                        <th>Click-through URL</th>
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
