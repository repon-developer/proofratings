const ActiveSites = (props) => {
    const active_connections = Array.isArray(props.active_connections) ? props.active_connections : [];

    const handleCheck = (site_id) => {
        const index = active_connections.indexOf(site_id);
        if (index !== -1) {
            active_connections.splice(index, 1);
        } else {
            active_connections.push(site_id);
        }      
        
        props.onUpdate(active_connections);
    }

    const connections = proofratings.active_connections.map((slug) => {
        if ( typeof proofratings.review_sites[slug] !== 'object' ) {
            return false;
        }

        proofratings.review_sites[slug].slug = slug;
        return proofratings.review_sites[slug];
    }).filter(item => item !== false)

    return (
        <div className="connected-sites-wrapper">
            <h2 className='section-title-large'>Customize</h2>
            <div className="review-sites-checkboxes review-sites-checkboxes-widget">
                {connections.map(connection => (
                    <label key={connection.slug} className="checkbox-review-site">
                        <input type="checkbox" defaultChecked={active_connections.includes(connection.slug)} onClick={() => handleCheck(connection.slug)} />
                        <img src={connection.logo} alt={connection.name} />
                    </label>
                ))}
            </div>

            <p>Select/deselect which review sites you want to appear.</p>
        </div>
    );
};

export default ActiveSites;
