import store, { ACTIONS } from './Store';

const ReviewSites = (props) => {
    const settings = store.getState();
    const activeSites = Array.isArray(props?.activeSites) ? props.activeSites : [];

    const update_font = (e) => {
        store.dispatch({ type: ACTIONS.SAVE_SETTINGS, payload: {...settings, font: e.target.value} })
    }

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
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">Font Family</th>
                        <td>
                            <select value={settings?.font} onChange={update_font}>
                                <option value="Didact Gothic">Didact Gothic</option>
                                <option value="Metropolis">Metropolis</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
			</table>

            <div className="gap-30" />

            {activeSites.length > 0 && 
                <div className="connect-sites-container">
                    <h2>Connected Sites</h2>
                    <div className="review-sites-checkboxes">
                        {activeSites.map(site_id => (
                            <label key={site_id} className="checkbox-review-site">
                                <input type="checkbox" defaultChecked={true} onClick={() => handleCheck(site_id)} />
                                <img src={proofratings.review_sites[site_id].logo} alt={proofratings.review_sites[site_id].name} />
                            </label>
                        ))}
                    </div>
                </div>
            }

            {get_category_sites('general', 'General Review Sites')}

            {get_category_sites('home-service', 'Home Services Review Sites')}

            {get_category_sites('solar', 'Solar Review Sites')}

            {get_category_sites('software', 'SaaS/Software Review Sites')}
        </React.Fragment>
    );
};

export default ReviewSites;
