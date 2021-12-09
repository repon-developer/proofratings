const { useState } = React;

const ReviewSites = () => {

    const [activeSites, setActiveSites] = useState(['facebook']);

    const handleCheck = (site_id) => {        
        const index = activeSites.indexOf(site_id);
        if (index !== -1) {
            activeSites.splice(index, 1);
        } else {
            activeSites.push(site_id);
        }
        
        setActiveSites([...activeSites])
    }

    const get_category_sites = (category) => {      
        const get_category_sites = Object.keys(proofratings.review_sites).map(key => {
            return {id: key, ...proofratings.review_sites[key]};
        })
        .filter((site) => site.category === category)
        .filter(site => !activeSites.includes(site.id))
        
        if ( !get_category_sites.length ) {
            return;
        }

        return (
            <div className="review-sites-checkboxes">
                {get_category_sites.map(site => (
                    <label className="checkbox-review-site">
                        <input type="checkbox" checked={false} onClick={() => handleCheck(site.id)} />
                        <img src={site.logo} alt={site.name} />
                    </label>
                ))}                
            </div>
        )

    }

    return (
        <React.Fragment>

            <div className="review-sites-checkboxes">
                {activeSites.map(site_id => (
                    <label className="checkbox-review-site">
                        <input type="checkbox" checked={true} onClick={() => handleCheck(site_id)} />
                        <img src={proofratings.review_sites[site_id].logo} alt={proofratings.review_sites[site_id].name} />
                    </label>
                ))}
            </div>


            <h2>General Review Sites</h2>
            {get_category_sites('general')}

            <h2>Home Services Review Sites</h2>
            {get_category_sites('home-service')}

            <h2>Solar Review Sites</h2>
            {get_category_sites('solar')}

            <h2>SaaS/Software Review Sites</h2>
            {get_category_sites('software')}
        </React.Fragment>
    );
};

export default ReviewSites;
