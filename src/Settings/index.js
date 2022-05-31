const { useEffect, useState } = React;

import store, { ACTIONS } from './Store';
import SiteConnections from './Connections';
import Report from './Report';
import Schema from './Schema';

const ProofratingsSettings = () => {
    const [state, setState] = useState(store.getState().state);

    useEffect(() => {
        if (!Array.isArray(proofratings.locations)) {
            proofratings.locations = [];
        }


        const unsubscribe = store.subscribe(() => setState({ ...store.getState().state }))

        const location = typeof proofratings.locations[0] !== 'undefined' ? proofratings.locations[0] : { id: 'global' };
        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { location_id: location.id } });

        return () => unsubscribe();
    }, []);

    useEffect(() => {
        if (!state?.location_id) {
            return;
        }

        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { loading: true } });

        const request = jQuery.post(proofratings.ajaxurl, { action: 'get_proofratings_location_settings', location_id: state.location_id }, function (response) {
            if (response?.success == false) {
                return store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { error: true, loading: false } });
            }

            //console.log(response.settings)
            store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: response.settings });
            store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { error: false, loading: false } });
        });

        request.fail(function () {
            return setState({ ...state, error: true, loading: false });
        })


    }, [state.location_id]);

    console.log(proofratings);

    const setTab = (settings_tab, e) => {
        e.preventDefault();
        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { settings_tab } });
    }

    const save_data = () => {
        if (state.saving) {
            return;
        }

        const settings = Object.assign({ action: 'save_proofratings_location_settings', location_id: state.location_id }, store.getState().settings);
        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { saving: true } });

        settings.action = 'save_proofratings_location_settings';
        const request = jQuery.post(proofratings.ajaxurl, settings, (response) => {
            console.log('After Saved', response.data)
            store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { saving: false } });
        })

        request.fail(() => {
            store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { saving: false } });
            alert('Something went wrong');
        })
    }

    if (state.loading === true) {
        return <div className="proofraing-progress-msg">Loading...</div>
    }

    if (state.error === true) {
        return <div className="proofraing-progress-msg">Failed to retrive this location.</div>
    }

    const tabs = {
        'connections': 'Site Connections',
        'report': 'Monthly Report',
        'schema': 'Schema'
    }

    const current_tab = state?.settings_tab || 'connections';

    const handle_location = (location_id) => {
        store.dispatch({ type: ACTIONS.UPDATE_STATE, payload: { location_id } });
    }

    const get_location_dropdown = () => {
        if (!Array.isArray(proofratings.locations)) {
            proofratings.locations = [];
        }

        return (
            <div className="location-dropdown">
                <label>Location</label>
                <select className="location-select" defaultValue={state?.location_id} onChange={(e) => handle_location(e.target.value)} >
                    {proofratings.locations.map(location => <option key={location.id} value={location.id}>{location.name}</option>)}
                </select>
            </div>
        )
    }

    const is_overall = proofratings?.location_id === 'overall';

    return (
        <React.Fragment>
            <header className="proofratins-header">
                <a className="btn-back-main-menu" href="/wp-admin/admin.php?page=proofratings"><i className="icon-back fa-solid fa-angle-left"></i> Back to Main Menu</a>
                <h1 className="title">Settings</h1>

                {proofratings?.global && get_location_dropdown()}

                <div className="rating-badges-navtab">
                    {Object.keys(tabs).map((key) => {
                        const tab_class = (current_tab === key) ? 'active' : '';
                        return <a key={key} href="#" onClick={(e) => setTab(key, e)} className={tab_class}>{tabs[key]}</a>
                    })}
                </div>
            </header>

            {current_tab === 'connections' && <SiteConnections location_id={state.location_id} />}
            {current_tab === 'report' && <Report />}
            {current_tab === 'schema' && <Schema />}

            <div className="form-footer">
                <button className="button button-primary btn-save" onClick={save_data}>{state.saving ? 'Saving...' : 'SAVE CHANGE'}</button>
                <a className='btn-cancel' href="/wp-admin/admin.php?page=proofratings">CANCEL</a>
            </div>

        </React.Fragment>
    );
};

const proofratings_settings_root = document.getElementById("proofratings-settings-root");
if (proofratings_settings_root) {
    ReactDOM.render(<ProofratingsSettings />, proofratings_settings_root);
}