import store, { ACTIONS } from './Store';

const ReviewSites = (props) => {
    const activeSites = Array.isArray(props?.activeSites) ? props.activeSites : [];

    const handleCheck = (site_id) => {
        const index = activeSites.indexOf(site_id);
        if (index !== -1) {
            activeSites.splice(index, 1);
        } else {
            activeSites.push(site_id);
        }

        store.dispatch({ type: ACTIONS.ACTIVE_SITES, payload: activeSites });
    }

    const get_category_sites = (category, title) => {      
        const get_category_sites = Object.keys(proofratings.review_sites).map(key => {
            return {id: key, ...proofratings.review_sites[key]};
        })
        .filter((site) => site.category === category)
        .filter(site => !activeSites.includes(site.id))
        
        if ( !get_category_sites.length ) {
            return;
        }

        return (
            <React.Fragment>
                <h2>{title}</h2>
                <div className="review-sites-checkboxes">
                    {get_category_sites.map(site => (
                        <label key={site.id} className="checkbox-review-site">
                            <input type="checkbox" onClick={() => handleCheck(site.id)} />
                            <img src={site.logo} alt={site.name} />
                        </label>
                    ))}                
                </div>
            </React.Fragment>
        )
    }

    return (
        <React.Fragment>

            <div className="review-sites-checkboxes">
                {activeSites.map(site_id => (
                    <label key={site_id} className="checkbox-review-site">
                        <input type="checkbox" defaultChecked={true} onClick={() => handleCheck(site_id)} />
                        <img src={proofratings.review_sites[site_id].logo} alt={proofratings.review_sites[site_id].name} />
                    </label>
                ))}
            </div>

            {get_category_sites('general', 'General Review Sites')}

            {get_category_sites('home-service', 'Home Services Review Sites')}

            {get_category_sites('solar', 'Solar Review Sites')}

            {get_category_sites('software', 'SaaS/Software Review Sites')}
        </React.Fragment>
    );
};

export default ReviewSites;
