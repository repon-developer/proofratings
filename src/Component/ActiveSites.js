import {get_connections } from '../widgets/Store';

const ActiveSites = (props) => {
    const widget_connections = Array.isArray(props.widget_connections) ? props.widget_connections : [];

    const handleCheck = (site_id) => {
        const index = widget_connections.indexOf(site_id);
        if (index !== -1) {
            widget_connections.splice(index, 1);
        } else {
            widget_connections.push(site_id);
        }      
        
        props.onUpdate(widget_connections);
    }

    const connections = get_connections();

    if ( connections.length === 0) {
        return <div className="no-connection">Please select a connection from settings</div>
    }

    return (
        <div className="connected-sites-wrapper">
            <h2 className='section-title-large'>Customize</h2>
            <div className="review-sites-checkboxes review-sites-checkboxes-widget">
                {connections.map(connection => (
                    <label key={connection.slug} className={`checkbox-review-site ${connection.approved ? '' : 'has-pending'}`}>
                        {connection.approved && <input type="checkbox" defaultChecked={widget_connections.includes(connection.slug)} onClick={() => handleCheck(connection.slug)} />}                        
                        <img src={connection.logo} alt={connection.name} />
                    </label>
                ))}
            </div>

            <p>Select/deselect which review sites you want to appear.</p>
        </div>
    );
};

export default ActiveSites;
