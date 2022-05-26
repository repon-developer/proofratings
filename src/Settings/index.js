const { useEffect, useState } = React;

import store, { ACTIONS } from './Store';
import SiteConnections from './Connections';
import Report from './Report';
import Schema from './Schema';

const ProofratingsSettings = () => {
    const [state, setState ] = useState({
        error: null,
        loading: true,
        saving: false,
        current_tab: 'connections',
    });

    useEffect(() => {

        const unsubscribe = store.subscribe(() => setState(store.getState()))
        


        const request = jQuery.post(proofratings.ajaxurl, {action: 'proofratings_get_location_settings'}, function (response) {
            console.log(response.data, proofratings);
            if ( response?.success == false ) {
                return setState({...state, error: true, loading: false});
            }
            
            store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: response.data });
            setState({...state, error: false, loading: false});
        });

        request.fail(function() {
            return setState({...state, error: true, loading: false});
        })

        return () => unsubscribe();
    }, []);

    const setTab = (current_tab, e) => {
        e.preventDefault();
        setState({...state, current_tab})
    }

    const save_data = () => {
        if ( state.saving ) {
            return;
        }

        setState({...state, saving: true});
        
        const settings = store.getState();
        settings.action = 'save_proofratings_location_settings';
        settings.location = 'global';

        const request = jQuery.post(proofratings.ajaxurl, settings, (response) => {
            console.log(response.data);
            setState({...state, saving: false})
        })
        
        request.fail(() => {
            setState({...state, saving: false})
            alert('Something went wrong');
        })
    }
    
    if ( state.loading === true) {
        return <div className="proofraing-progress-msg">Loading...</div>
    }
    
    if ( state.error === true) {
        return <div className="proofraing-progress-msg">Failed to retrive this location.</div>
    }

    const tabs = {
        'connections': 'Site Connections',
        'report': 'Monthly Report',
        'schema': 'Schema'
    }

    const current_tab = state?.current_tab || 'badge-overview';

    //console.log(settings);


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