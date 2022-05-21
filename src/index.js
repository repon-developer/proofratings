const proofratings_widgets_root = document.getElementById(
    "proofratings-root"
);

import store, { ACTIONS } from './Store';

import ReviewSites from './Sites';
import BadgeDisplay from './BadgeDisplay';
import BadgeSquare from './BadgeSquare'
import BadgeBasic from './BadgeBasic'
import Sites_Icon from './SitesIcon'
import BadgeRectangle from './BadgeRectangle'

import OverallRectangleEmbed from './OverallRectangle/Embed'
import OverallRectangleFloat from './OverallRectangle/Float'

import OverallNarrowEmbed from './OverallNarrow/Embed';
import OverallNarrowFloat from './OverallNarrow/Float';

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
        const request = jQuery.post(proofratings.ajaxurl, {location_id, action: 'proofratings_get_location'}, function (response) {
            if ( response?.success == false ) {
                return setState({...state, error: true, loading: false});
            }
            
            setState({...state, error: false, loading: false});
            if ( Object.keys(response).length !== 0 ) {
                store.dispatch({ type: ACTIONS.SAVE_SETTINGS, payload: response });
            }
        });

        request.fail(function() {
            return setState({...state, error: true, loading: false});
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
        return <div className="proofraing-progress-msg">Failed to retrive this location.</div>
    }

    const tabs = {
        'review-sites': 'Review Sites',
        'display-badges': 'Badges',
        'badge-square': 'Sites Square',
        'badge-basic': 'Sites Basic',
        'sites-icon': 'Sites Icon',
        'badge-rectangle': 'Sites (Rectangle)',
        'overall-rectangle-embed': 'Overall Rating Rectangle - EMBED',
        'overall-rectangle-float': 'Overall Rating Rectangle - FLOAT',
        'overall-narrow-embed': 'Overall Rating Narrow - EMBED',
        'overall-narrow-float': 'Overall Rating Narrow - FLOAT',
        'badge-popup': 'Popup Badges',
        'overall-cta-banner': 'Overall Rating (CTA Banner)',
    }

    
    const { badge_display, activeSites } = settings;
    
    if ( badge_display?.sites_square !== true ) {
        delete tabs['badge-square'];
    }

    if ( badge_display?.badge_basic !== true ) {
        delete tabs['badge-basic'];
    }

    if ( badge_display?.sites_icon !== true ) {
        delete tabs['sites-icon'];
    }

    if ( badge_display?.sites_rectangle !== true ) {
        delete tabs['badge-rectangle'];
    }

    if ( badge_display?.overall_rectangle_embed !== true ) {
        delete tabs['overall-rectangle-embed']
    }

    if ( badge_display?.overall_rectangle_float !== true ) {
        delete tabs['overall-rectangle-float']
    }

    if ( badge_display?.overall_narrow_embed !== true ) {
        delete tabs['overall-narrow-embed']
    }

    if ( badge_display?.overall_narrow_float !== true ) {
        delete tabs['overall-narrow-float']
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
            <header className="proofratins-header">
				<a className="btn-back-main-menu" href="#">Back to Main Menu</a>
				<h1 className="title">Rating Badges</h1>

                <div className="rating-badges-navtab">
                {Object.keys(tabs).map((key) => {
                    const tab_class = (current_tab === key) ? 'active' : '';
                    return <a key={key} href="#" onClick={(e) => setTab(key, e)} className={tab_class}>{tabs[key]}</a>
                })}
                </div>
			</header>

            {current_tab === 'review-sites' && <ReviewSites activeSites={activeSites} id={location_id} />}
            {current_tab === 'display-badges' && <BadgeDisplay badge_display={badge_display} id={location_id} />}
            {current_tab === 'badge-square' && <BadgeSquare id={location_id} />}
            {current_tab === 'badge-rectangle' && <BadgeRectangle id={location_id} />}
            {current_tab === 'badge-basic' && <BadgeBasic id={location_id} />}
            {current_tab === 'sites-icon' && <Sites_Icon id={location_id} />}
            
            {current_tab === 'overall-rectangle-embed' && <OverallRectangleEmbed id={location_id} />}
            {current_tab === 'overall-rectangle-float' && <OverallRectangleFloat id={location_id} />}

            {current_tab === 'overall-narrow-embed' && <OverallNarrowEmbed id={location_id} />}
            {current_tab === 'overall-narrow-float' && <OverallNarrowFloat id={location_id} />}

            {current_tab === 'badge-popup' && <OverallPopup />}
            {current_tab === 'overall-cta-banner' && <CTABanner id={location_id} />}

            <p className="submit">
                <button id="btn-proofratings-save" className="button button-primary" onClick={save_data}>{state.saving ? 'Saving...' : 'Save Changes'}</button>
            </p>

        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    ReactDOM.render(<ProofratingsWidgets />, proofratings_widgets_root);
}
