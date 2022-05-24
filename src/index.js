const proofratings_widgets_root = document.getElementById(
    "proofratings-root"
);

import store, { ACTIONS } from './Store';

import BadgeDisplay from './BadgeDisplay';
import BadgeSquare from './BadgeSquare'
import BadgeBasic from './BadgeBasic'
import Sites_Icon from './SitesIcon'
import BadgeRectangle from './BadgeRectangle'

import OverallRectangleEmbed from './OverallRectangle/Embed'
import OverallRectangleFloat from './OverallRectangle/Float'

import OverallNarrowEmbed from './OverallNarrow/Embed';
import OverallNarrowFloat from './OverallNarrow/Float';

import CTABanner from './CTABanner';

const { useEffect, useState, useRef } = React;

const location_id = proofratings_widgets_root.getAttribute("data-location");

const ProofratingsWidgets = () => {
    const [state, setState] = useState({
        error: null,
        loading: true,
        saving: false,
    });

    const [settings, setSettings] = useState(store.getState());

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings({ ...store.getState() }))
        return () => unsubscribe();
    }, [])

    const support_button = useRef(null);

    useEffect(() => {
        if ( support_button.current ) {
            tippy(support_button.current, { content: 'Need Help?' });
        }        
        
    }, [support_button.current])

    const setTab = (current_tab, e) => {
        e.preventDefault();
        store.dispatch({ type: ACTIONS.SAVE_SETTINGS, payload: { ...settings, current_tab } });
    }

    useEffect(() => {
        const request = jQuery.post(proofratings.ajaxurl, { location_id, action: 'proofratings_get_location' }, function (response) {
            //console.log(response, proofratings)
            if (response?.success == false) {
                return setState({ ...state, error: true, loading: false });
            }

            setState({ ...state, error: false, loading: false });
            if (Object.keys(response).length !== 0) {
                store.dispatch({ type: ACTIONS.SAVE_SETTINGS, payload: response });
            }
        });

        request.fail(function () {
            return setState({ ...state, error: true, loading: false });
        })
    }, []);

    const save_data = () => {
        if (state.saving) {
            return;
        }

        setState({ ...state, saving: true });

        settings.action = 'proofratings_save_location';
        settings.location_id = location_id;

        jQuery.post(proofratings.ajaxurl, settings, function (response) {
            if (response?.success == false) {
                alert('Something wrong with saving data')
            }

            setState({ ...state, saving: false })
        })
    }


    if (state.loading === true) {
        return <div className="proofraing-progress-msg">Loading...</div>
    }

    if (state.error === true) {
        return <div className="proofraing-progress-msg">Failed to retrive this location.</div>
    }

    const tabs = {
        'overview': 'Badge Overview',
        'square': 'Square',
        'basic': 'Basic',
        'icon': 'Icon',
        'rectangle': 'Rectangle',
        'overall-rectangle-embed': 'Overall Rectangle (EMBED)',
        'overall-rectangle-float': 'Overall Rectangle (FLOAT)',
        'overall-narrow-embed': 'Overall Narrow (EMBED)',
        'overall-narrow-float': 'Overall Narrow (Float)',
        'overall-cta-banner': 'Banner',
    }


    const { badge_display } = settings;

    if (badge_display?.square !== true) {
        delete tabs['square'];
    }

    if (badge_display?.basic !== true) {
        delete tabs['basic'];
    }

    if (badge_display?.icon !== true) {
        delete tabs['icon'];
    }

    if (badge_display?.rectangle !== true) {
        delete tabs['rectangle'];
    }

    if (badge_display?.overall_rectangle_embed !== true) {
        delete tabs['overall-rectangle-embed']
    }

    if (badge_display?.overall_rectangle_float !== true) {
        delete tabs['overall-rectangle-float']
    }

    if (badge_display?.overall_narrow_embed !== true) {
        delete tabs['overall-narrow-embed']
    }

    if (badge_display?.overall_narrow_float !== true) {
        delete tabs['overall-narrow-float']
    }

    if (badge_display?.overall_cta_banner !== true) {
        delete tabs['overall-cta-banner'];
    }

    const current_tab = settings?.current_tab || 'overview';

    return (
        <React.Fragment>
            <header className="proofratins-header">
                <div className="header-row">
                    <div className="header-left">
                        <a className="btn-back-main-menu" href="/wp-admin/admin.php?page=proofratings"><i className="icon-back fa-solid fa-angle-left" /> Back to Main Menu</a>
                        <h1 className="title">Rating Badges</h1>
                    </div>

                    <div className="header-right">
                        <a ref={support_button} className="btn-support fa-regular fa-circle-question" href="/wp-admin/admin.php?page=proofratings-support" />
                    </div>
                </div>

                <div className="rating-badges-navtab">
                    {Object.keys(tabs).map((key) => {
                        const tab_class = (current_tab === key) ? 'active' : '';
                        return <a key={key} href="#" onClick={(e) => setTab(key, e)} className={tab_class}>{tabs[key]}</a>
                    })}
                </div>
            </header>

            {current_tab === 'overview' && <BadgeDisplay badge_display={badge_display} id={location_id} />}
            {current_tab === 'square' && <BadgeSquare id={location_id} />}
            {current_tab === 'basic' && <BadgeBasic id={location_id} />}
            {current_tab === 'icon' && <Sites_Icon id={location_id} />}
            {current_tab === 'rectangle' && <BadgeRectangle id={location_id} />}

            {current_tab === 'overall-rectangle-embed' && <OverallRectangleEmbed id={location_id} />}
            {current_tab === 'overall-rectangle-float' && <OverallRectangleFloat id={location_id} />}

            {current_tab === 'overall-narrow-embed' && <OverallNarrowEmbed id={location_id} />}
            {current_tab === 'overall-narrow-float' && <OverallNarrowFloat id={location_id} />}

            {current_tab === 'overall-cta-banner' && <CTABanner id={location_id} />}

            <div className="form-footer">
                <button id="btn-proofratings-save" className="button button-primary" onClick={save_data}>{state.saving ? 'Saving...' : 'SAVE CHANGE'}</button>
                <a className='btn-cancel' href="/wp-admin/admin.php?page=proofratings">CANCEL</a>
            </div>

        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    ReactDOM.render(<ProofratingsWidgets />, proofratings_widgets_root);
}
