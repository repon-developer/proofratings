const { useEffect, useState } = React;

import store, { ACTIONS } from './Store';
import SiteConnections from './Connections';
import Report from './Report';
import Schema from './Schema';

const ProofratingsSettings = () => {
    const [state, setState] = useState({
        error: null,
        loading: true,
        saving: false,
        location_id: null,
        current_tab: 'connections',
    });

    useEffect(() => {
        if (!state?.location_id) {
            return;
        }

        setState({ ...state, loading: true });

        const request = jQuery.post(proofratings.ajaxurl, { action: 'get_proofratings_location_settings', location_id: state.location_id }, function (response) {
            //console.log(response.settings)
            if (response?.success == false) {
                return setState({ ...state, error: true, loading: false });
            }

            store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: {...response.settings, location_id: state.location_id} });
            setState({ ...state, error: false, loading: false });
        });

        request.fail(function () {
            return setState({ ...state, error: true, loading: false });
        })


    }, [state.location_id]);

    useEffect(() => {        
        if (!Array.isArray(proofratings.locations)) {
            proofratings.locations = [];
        }

        const location = typeof proofratings.locations[0] !== 'undefined' ? proofratings.locations[0] : {id: 'global'};
        setState({...state, location_id: location.id})

        const unsubscribe = store.subscribe(() => {
            setState({ location_id: store.getState().location_id })
        })

        return () => unsubscribe();
    }, []);

    const setTab = (current_tab, e) => {
        e.preventDefault();
        setState({ ...state, current_tab })
    }

    const save_data = () => {
        if (state.saving) {
            return;
        }
        
        const settings = store.getState();
        
        setState({ ...state, saving: true });

        settings.action = 'save_proofratings_location_settings';
        const request = jQuery.post(proofratings.ajaxurl, settings, (response) => {
            setState({ ...state, saving: false })
        })

        request.fail(() => {
            setState({ ...state, saving: false })
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

    const current_tab = state?.current_tab || 'connections';

    return (
        <React.Fragment>
            <header className="proofratins-header">
                <a className="btn-back-main-menu" href="/wp-admin/admin.php?page=proofratings"><i className="icon-back fa-solid fa-angle-left"></i> Back to Main Menu</a>
                <h1 className="title">Settings</h1>

                <div className="rating-badges-navtab">
                    {Object.keys(tabs).map((key) => {
                        const tab_class = (current_tab === key) ? 'active' : '';
                        return <a key={key} href="#" onClick={(e) => setTab(key, e)} className={tab_class}>{tabs[key]}</a>
                    })}
                </div>
            </header>

            {current_tab === 'connections' && <SiteConnections />}
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