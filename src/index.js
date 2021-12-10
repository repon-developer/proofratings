const proofratings_widgets_root = document.getElementById(
    "proofratings-widgets-root"
);

import ReviewSites from './Sites';
import BadgeDisplay from './BadgeDisplay';

const { useEffect, useState } = React;

const ProofratingsWidgets = () => {
    const [state, setState ] = useState({
        error: null,
        current_tab: 'display-badges'
    });

    const [settings, setSettings ] = useState({
        activeSites: ['facebook'],
        badge_display: {
            sites_square: true,
            sites_rectangle: true,
        }
    });

    const update_settings = (args) => setSettings({...settings, ...args})

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

    console.log(settings)

    const { activeSites, badge_display } = settings;

    return (
        <React.Fragment>
            <h2 className="nav-tab-wrapper">
                {Object.keys(tabs).map((key) => {
                    const tab_class = (state.current_tab === key) ? 'nav-tab-active' : '';
                    return <a href="#" onClick={(e) => setTab(key, e)} className={`nav-tab ${tab_class}`}>{tabs[key]}</a>
                })}
            </h2>

            {state.current_tab === 'review-sites' && <ReviewSites updateSettings={update_settings} activeSites={activeSites} />}
            {state.current_tab === 'display-badges' && <BadgeDisplay updateSettings={update_settings} badge_display={badge_display} />}

        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    ReactDOM.render(<ProofratingsWidgets />, proofratings_widgets_root);
}
