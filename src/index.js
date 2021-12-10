const proofratings_widgets_root = document.getElementById(
    "proofratings-widgets-root"
);

import store, { ACTIONS } from './Store';

import ReviewSites from './Sites';
import BadgeDisplay from './BadgeDisplay';
import BadgeSquare from './BadgeSquare'

const { useEffect, useState } = React;

const ProofratingsWidgets = () => {
    const [state, setState ] = useState({
        error: null,
        current_tab: 'badge-square'
    });

    const [settings, setSettings] = useState(store.getState());
    store.subscribe(() => setSettings({...store.getState()}))    

    const setTab = (current_tab, e) => {
        e.preventDefault();
        setState({...state, current_tab})
    }

    useEffect(() => {       
        const location_id = proofratings_widgets_root.getAttribute("data-location");
        jQuery.post(proofratings.ajaxurl, {location_id, action: 'proofratings_get_location'}, function (response) {
            console.log(response);
            if ( response?.success == false ) {
                //return setError(true);
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
        'overall-rectangle': 'Overall Rating (Rectangle)',
        'overall-narrow': 'Overall Rating (Narrow)',
        'badge-popup': 'Popup Badges',
        'overall-cta-banner': 'Overall Rating (CTA Banner)',
    }

    
    const { badge_display, activeSites } = settings;
    
    if ( badge_display.sites_square !== true ) {
        delete tabs['badge-square'];
    }

    if ( badge_display.sites_rectangle !== true ) {
        delete tabs['badge-rectangle'];
    }

    if ( badge_display?.overall_rectangle?.embed !== true && badge_display?.overall_rectangle?.float !== true ) {
        delete tabs['overall-rectangle']
    }

    if ( badge_display?.overall_narrow?.embed !== true && badge_display?.overall_narrow?.float !== true ) {
        delete tabs['overall-narrow']
    }

    if ( badge_display?.overall_cta_banner !== true ) {
        delete tabs['overall-cta-banner'];
    }

    if ( badge_display?.overall_rectangle?.float !== true && badge_display?.overall_narrow?.float !== true) {
        delete tabs['badge-popup'];
    }

    console.log(settings)
    
    return (
        <React.Fragment>
            <h2 className="nav-tab-wrapper">
                {Object.keys(tabs).map((key) => {
                    const tab_class = (state.current_tab === key) ? 'nav-tab-active' : '';
                    return <a href="#" onClick={(e) => setTab(key, e)} className={`nav-tab ${tab_class}`}>{tabs[key]}</a>
                })}
            </h2>

            {state.current_tab === 'review-sites' && <ReviewSites activeSites={activeSites} />}
            {state.current_tab === 'display-badges' && <BadgeDisplay badge_display={badge_display} />}
            {state.current_tab === 'badge-square' && <BadgeSquare />}

        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    ReactDOM.render(<ProofratingsWidgets />, proofratings_widgets_root);
}
