const proofratings_widgets_root = document.getElementById(
    "proofratings-root"
);

import store, { ACTIONS } from './widgets/Store';

import BadgeDisplay from './widgets/BadgeDisplay';
import BadgeSquare from './widgets/BadgeSquare'
import BadgeBasic from './widgets/BadgeBasic'
import Sites_Icon from './widgets/SitesIcon'
import BadgeRectangle from './widgets/BadgeRectangle'

import OverallRectangleEmbed from './OverallRectangle/Embed'

import OverallRectangleFloat from './OverallRectangle/Float'

import OverallNarrowEmbed from './OverallNarrow/Embed';
import OverallNarrowFloat from './OverallNarrow/Float';

import CTABanner from './widgets/CTABanner';

const { useEffect, useState, useRef } = React;


const ProofratingsWidgets = (props) => {
    const location_id = props.location_id;
    const [state, setState] = useState({ error: null, loading: true, saving: false, location_name: '' });
    const [settings, setSettings] = useState(store.getState());

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
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { ...settings, currently_editing: false, current_tab } });
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
                delete response.settings.current_tab;
                store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: response.settings });
            }
        });

        request.fail(function () {
            return setState({ ...state, error: true, loading: false });
        })
    }, []);

    const save_data = (updated_settings = false) => {
        if (state.saving) {
            return;
        }

        setState({ ...state, saving: true });

        const new_settings = updated_settings === false ? settings : updated_settings;
        new_settings.action = 'save_proofratings_location_settings';
        new_settings.location_id = location_id;

        jQuery.post(proofratings.ajaxurl, new_settings, function (response) {
            if (response?.success == false) {
                alert('Something wrong with saving data')
            }

            if ( updated_settings === false ) {
                //store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { current_tab: 'overview' } });
            }

            setState({ ...state, saving: false })
        })
    }

    const handle_cancel = (e) => {
        e.preventDefault();
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { current_tab: 'overview' } });
    }

    if (state.loading === true) {
        return <div className="proofraing-progress-msg">Loading...</div>
    }

    if (state.error === true) {
        return <div className="proofraing-progress-msg">Failed to retrive this location.</div>
    }

    const tabs = { 'overview': 'Badge Overview' }
    if (settings.current_tab !== 'overview') {
        tabs.edit_tab = 'Edit Badge';
    }


    const { badge_display } = settings;

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
                    <a href="#" onClick={(e) => setTab('overview', e)} className={current_tab === 'overview' ? 'active' : ''}>Badge Overview</a>
                    {current_tab !== 'overview' && <a href="#" className='active'>Edit Badge</a>}
                </div>
            </header>

            {current_tab === 'overview' && <BadgeDisplay badge_display={badge_display} id={location_id} save_now={save_data} />}
            {current_tab === 'widget_square' && <BadgeSquare id={location_id} />}
            {current_tab === 'widget_basic' && <BadgeBasic id={location_id} />}
            {current_tab === 'widget_icon' && <Sites_Icon id={location_id} />}
            {current_tab === 'widget_rectangle' && <BadgeRectangle id={location_id} />}
            {current_tab === 'overall_rectangle_embed' && <OverallRectangleEmbed id={location_id} />}
            {current_tab === 'overall_rectangle_float' && <OverallRectangleFloat id={location_id} />}
            {current_tab === 'overall_narrow_embed' && <OverallNarrowEmbed id={location_id} />}
            {current_tab === 'overall_narrow_float' && <OverallNarrowFloat id={location_id} />}
            {current_tab === 'overall_cta_banner' && <CTABanner id={location_id} />}

            {current_tab !== 'overview' &&
                <div className="form-footer">
                    <button id="btn-proofratings-save" className="button button-primary" onClick={() => save_data()}>{state.saving ? 'Saving...' : 'SAVE CHANGE'}</button>
                    <a onClick={handle_cancel} className='btn-cancel' href="#">CANCEL</a>
                </div>
            }
        </React.Fragment>
    );
};

if (proofratings_widgets_root) {
    const location_id = proofratings_widgets_root.getAttribute("data-location");
    ReactDOM.render(<ProofratingsWidgets location_id={location_id} />, proofratings_widgets_root);
}
