const proofratings_widgets_root = document.getElementById(
    "proofratings-widgets-root"
);

import ReviewSites from './Sites';

const { useEffect, useState } = React;

const ProofratingsWidgets = () => {
    const [state, setState ] = useState({
        error: null,
        current_tab: 'review-sites'
    });

    const [settings, setSettings ] = useState();

    const setTab = (current_tab, e) => {
        e.preventDefault();
        setState({...state, current_tab})
    }



    useEffect(() => {
        

        const location_id = proofratings_widgets_root.getAttribute("data-location");

        jQuery.post(proofratings.ajaxurl, {location_id, action: 'proofratings_get_location'}, function (response) {
            console.log(response);
            if ( response?.success == false ) {
                return setError(true);
            }

          
        })
    }, []);

    if ( state.error === true) {
        return <div>No Location found</div>
    }

    const tabs = {
        'review-sites': 'Review Sites',
        'display-badges': 'Badges',
        'badge-square': 'Sites (Square)',
        'badge-rectangle': 'Sites (Rectangle)',
        'overall-ratings-rectangle': 'Overall Rating (Rectangle)',
        'overall-ratings-narrow': 'Overall Rating (Narrow)',
        'badge-popup': 'Popup Badges',
        'overall-ratings-cta-banner': 'Overall Rating (CTA Banner)',
    }

    return (
        <React.Fragment>
            <h2 className="nav-tab-wrapper">
                {Object.keys(tabs).map((key) => {
                    const tab_class = (state.current_tab === key) ? 'nav-tab-active' : '';
                    return <a href="#" onClick={(e) => setTab(key, e)} className={`nav-tab ${tab_class}`}>{tabs[key]}</a>
                })}
                
                {/* <a href="#" onClick={() => setTab('review-sites')} className="nav-tab nav-tab-active">Review Sites</a>
                <a href="#" onClick={() => setTab('display-badges')} className="nav-tab">Badges</a>
                <a href="#" onClick={() => setTab('badge-square')} className="nav-tab" style={{}}>Sites (Square)</a>
                <a href="#" onClick={() => setTab('badge-rectangle')} className="nav-tab" style={{display: 'none'}}>Sites (Rectangle)</a>
                <a href="#" onClick={() => setTab('overall-ratings-rectangle')} className="nav-tab" style={{}}>Overall Rating (Rectangle)</a>

                <a href="#settings-overall-ratings-narrow" className="nav-tab" style={{}}>Overall Rating (Narrow)</a>
                <a href="#settings-badge-popup" className="nav-tab" style={{}}>Popup Badges</a>
                <a href="#settings-overall-ratings-cta-banner" className="nav-tab" style={{}}>Overall Rating (CTA Banner)</a> */}
            </h2>

            {state.current_tab === 'review-sites' && <ReviewSites />}

        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    ReactDOM.render(<ProofratingsWidgets />, proofratings_widgets_root);
}
