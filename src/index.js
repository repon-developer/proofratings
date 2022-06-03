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


const ProofratingsWidgets = (props) => {
    const location_id = props.location_id;
    const [state, setState] = useState({ error: null, loading: true, saving: false, location_name: '' });
    const [settings, setSettings] = useState(store.getState());

    console.log(settings)

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings({ ...store.getState() }))
        return () => unsubscribe();
    }, [])

    const support_button = useRef(null);

    useEffect(() => {
        if (support_button.current) {
            tippy(support_button.current, { content: 'Need Help?' });
        }

    }, [support_button.current])

    const setTab = (current_tab, e) => {
        e.preventDefault();
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { ...settings, current_tab } });
    }

    useEffect(() => {
        const request = jQuery.post(proofratings.ajaxurl, { location_id, action: 'get_proofratings_location_settings' }, function (response) {
            console.log(response)
            if (response?.success == false) {
                return setState({ ...state, error: true, loading: false });
            }

            let location_name = 'Rating Badges';
            if (response?.global === false) {
                location_name = location_name + ` (${response.location_name})`;
            }

            setState({ ...state, error: false, loading: false, location_name });
            if (typeof response?.settings === 'object') {
                store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: response.settings });
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

        settings.action = 'save_proofratings_location_settings';
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

    const tabs = {'overview': 'Badge Overview'}
    if ( settings.currently_editing ) {
        tabs.edit_tab = 'Edit Badge';
    }


    const { badge_display } = settings;

    // if (badge_display?.widget_square !== true) {
    //     delete tabs['widget_square'];
    // }

    // if (badge_display?.widget_basic !== true) {
    //     delete tabs['widget_basic'];
    // }

    // if (badge_display?.widget_icon !== true) {
    //     delete tabs['widget_icon'];
    // }

    // if (badge_display?.widget_rectangle !== true) {
    //     delete tabs['widget_rectangle'];
    // }

    // if (badge_display?.overall_rectangle_embed !== true) {
    //     delete tabs['overall-rectangle-embed']
    // }

    // if (badge_display?.overall_rectangle_float !== true) {
    //     delete tabs['overall-rectangle-float']
    // }

    // if (badge_display?.overall_narrow_embed !== true) {
    //     delete tabs['overall-narrow-embed']
    // }

    // if (badge_display?.overall_narrow_float !== true) {
    //     delete tabs['overall-narrow-float']
    // }

    // if (badge_display?.overall_cta_banner !== true) {
    //     delete tabs['overall-cta-banner'];
    // }

    const current_tab = settings?.current_tab || 'overview';

    return (
        <React.Fragment>
            <header className="proofratins-header">
                <div className="header-row">
                    <div className="header-left">
                        <a className="btn-back-main-menu" href="/wp-admin/admin.php?page=proofratings"><i className="icon-back fa-solid fa-angle-left" /> Back to Main Menu</a>
                        <h1 className="title">{state.location_name}</h1>
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
            {current_tab === 'widget_square' && <BadgeSquare id={location_id} />}
            {current_tab === 'widget_basic' && <BadgeBasic id={location_id} />}
            {current_tab === 'widget_icon' && <Sites_Icon id={location_id} />}
            {current_tab === 'widget_rectangle' && <BadgeRectangle id={location_id} />}

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
    const location_id = proofratings_widgets_root.getAttribute("data-location");
    ReactDOM.render(<ProofratingsWidgets location_id={location_id} />, proofratings_widgets_root);
}
