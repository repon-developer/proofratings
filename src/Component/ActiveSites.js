import store from './../Store';

const ActiveSites = (props) => {
    const { activeSites } = store.getState();

    const active_sites = Array.isArray(props.active_sites) ? props.active_sites : [];

    const handleCheck = (site_id) => {
        const index = active_sites.indexOf(site_id);
        if (index !== -1) {
            active_sites.splice(index, 1);
        } else {
            active_sites.push(site_id);
        }      
        
        props.onUpdate(active_sites);
    }

    const activated_sites = Array.isArray(activeSites) ? activeSites : [];

    return (
        <div className="connected-sites-wrapper">
            <h2 className='section-title-large'>Customize</h2>
            <div className="review-sites-checkboxes review-sites-checkboxes-widget">
                {activated_sites.map(site_id => (
                    <label key={site_id} className="checkbox-review-site">
                        <input type="checkbox" defaultChecked={active_sites.includes(site_id)} onClick={() => handleCheck(site_id)} />
                        <img src={proofratings.review_sites[site_id].logo} alt={proofratings.review_sites[site_id].name} />
                    </label>
                ))}
            </div>

            <p>Select/deselect which review sites you want to appear.</p>
        </div>
    );
};

export default ActiveSites;
