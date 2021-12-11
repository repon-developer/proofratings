const proofratings_widgets_root = document.getElementById(
    "proofratings-widgets-root"
);

import store, { ACTIONS } from './Store';

import ReviewSites from './Sites';
import BadgeDisplay from './BadgeDisplay';
import BadgeSquare from './BadgeSquare'
import BadgeRectangle from './BadgeRectangle'
import OverallRectangle from './OverallRectangle'
import OverallNarrow from './OverallNarrow';
import OverallPopup from './OverallPopup';
import CTABanner from './CTABanner';

const { useEffect, useState } = React;

const location_id = proofratings_widgets_root.getAttribute("data-location");

const ProofratingsWidgets = () => {
    const [state, setState ] = useState({
        error: null,
        loading: true,
        saving: false,
    });

    const [settings, setSettings] = useState(store.getState());

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings({...store.getState()}))
        return () => unsubscribe();
    }, [])


    const setTab = (current_tab, e) => {
        e.preventDefault();
        store.dispatch({ type: ACTIONS.SAVE_SETTINGS, payload: {...settings, current_tab} });
    }

    useEffect(() => {               
        jQuery.post(proofratings.ajaxurl, {location_id, action: 'proofratings_get_location'}, function (response) {
            console.log(response);
            if ( response?.success == false ) {
                return setState({...state, error: true, loading: false});
            }

            setState({...state, error: false, loading: false});
            store.dispatch({ type: ACTIONS.SAVE_SETTINGS, payload: response });
        })
    }, []);

    const save_data = () => {
        if ( state.saving ) {
            return;
        }
        
        setState({...state, saving: true});

        settings.action = 'proofratings_save_location';
        settings.location_id = location_id;

        jQuery.post(proofratings.ajaxurl, settings, function (response) {
            console.log(response);
            if ( response?.success == false ) {
                alert('Something wrong with saving data')
            }

            setState({...state, saving: false})
        })
    }

    
    if ( state.loading === true) {
        return <div className="proofraing-progress-msg">Loading...</div>
    }
    
    if ( state.error === true) {
        return <div className="proofraing-progress-msg">No Location found</div>
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
    
    if ( badge_display?.sites_square !== true ) {
        delete tabs['badge-square'];
    }

    if ( badge_display?.sites_rectangle !== true ) {
        delete tabs['badge-rectangle'];
    }

    if ( badge_display?.overall_rectangle_embed !== true && badge_display?.overall_rectangle_float !== true ) {
        delete tabs['overall-rectangle']
    }

    if ( badge_display?.overall_narrow_embed !== true && badge_display?.overall_narrow_float !== true ) {
        delete tabs['overall-narrow']
    }

    if ( badge_display?.overall_cta_banner !== true ) {
        delete tabs['overall-cta-banner'];
    }

    if ( badge_display?.overall_rectangle_float !== true && badge_display?.overall_narrow_float !== true) {
        delete tabs['badge-popup'];
    }

    const current_tab = settings?.current_tab || 'review-sites';



    return (
        <React.Fragment>
            <h2 className="nav-tab-wrapper">
                {Object.keys(tabs).map((key) => {
                    const tab_class = (current_tab === key) ? 'nav-tab-active' : '';
                    return <a key={key} href="#" onClick={(e) => setTab(key, e)} className={`nav-tab ${tab_class}`}>{tabs[key]}</a>
                })}
            </h2>

            {current_tab === 'review-sites' && <ReviewSites activeSites={activeSites} id={location_id} />}
            {current_tab === 'display-badges' && <BadgeDisplay badge_display={badge_display} id={location_id} />}
            {current_tab === 'badge-square' && <BadgeSquare id={location_id} />}
            {current_tab === 'badge-rectangle' && <BadgeRectangle id={location_id} />}
            {current_tab === 'overall-rectangle' && <OverallRectangle id={location_id} />}
            {current_tab === 'overall-narrow' && <OverallNarrow id={location_id} />}
            {current_tab === 'badge-popup' && <OverallPopup />}
            {current_tab === 'overall-cta-banner' && <CTABanner />}

            <p className="submit">
                <button id="btn-proofratings-save" className="button button-primary" onClick={save_data}>{state.saving ? 'Saving...' : 'Save Changes'}</button>
            </p>

        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    ReactDOM.render(<ProofratingsWidgets />, proofratings_widgets_root);
}
