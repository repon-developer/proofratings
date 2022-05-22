const { useEffect, useState } = React;

import SiteConnections from './Connections';

const ProofratingsSettings = () => {
    const [state, setState ] = useState({
        error: null,
        loading: true,
        saving: false,
        current_tab: 'connections',
    });
    

    const [settings, setSettings] = useState({});

    useEffect(() => {               
        const request = jQuery.post(proofratings.ajaxurl, {action: 'proofratings_get_settings'}, function (response) {
            console.log(response);

            if ( response?.success == false ) {
                return setState({...state, error: true, loading: false});
            }
            
            setState({...state, error: false, loading: false});
            setSettings({...settings });
        });

        request.fail(function() {
            return setState({...state, error: true, loading: false});
        })
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
        'connections': 'Site Connections',
        'report': 'Monthly Report',
        'schema': 'Schema'
    }

    const current_tab = state?.current_tab || 'badge-overview';


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
            {current_tab === 'report' && <SiteConnections />}
            {current_tab === 'schema' && <SiteConnections />}

            

            

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