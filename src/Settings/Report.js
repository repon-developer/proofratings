const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const Report = () => {
    const [email, setEmail] = useState('');
    const [settings, setSettings] = useState(store.getState())

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings(store.getState()))
        return () => unsubscribe();
    }, [])

    const automated_email_report = Object.assign({ active: false, emails: [] }, settings?.automated_email_report);

    const handle_email = () => {
        automated_email_report.active = !automated_email_report.active;
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { automated_email_report } });
    }

    const email_submit = (e) => {
        if (e.keyCode !== 13) {
            return;
        }

        const check_email = /\S+@\S+\.\S+/.test(email);

        if (check_email === false) {
            alert('Please enter email address and hit enter.');
            return false;
        }

        automated_email_report.emails.push(email)

        setEmail('');
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { automated_email_report } });
    }

    const remove_email = (i) => {
        automated_email_report.emails.splice(i, 1)
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { automated_email_report } });
    }

    const agency_settings = Object.assign({}, settings?.agency_settings);
    const update_agency = (key, value) => {
        agency_settings[key] = value;
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { agency_settings } });
    }

    return (
        <React.Fragment>
            <h2 className="section-title-large">Email Reporting</h2>

            <div className="email-reporting-wrap">
                <table className="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">Automated email report</th>
                            <td>
                                <input onClick={() => handle_email()} defaultChecked={automated_email_report.active} type="checkbox" className="checkbox-switch checkbox-yesno" />
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Email addresses</th>
                            <td>
                                <input onKeyDown={email_submit} onInput={(e) => setEmail(e.target.value)} value={email} type="email" />

                                <p style={{ fontStyle: 'italic', fontSize: 13, marginTop: 0 }}>Type email and hit enter</p>

                                <ul id="reporting-email-addresses">
                                    {automated_email_report.emails.map((email, i) => <li key={i}><span onClick={() => remove_email(i)} className="remove dashicons dashicons-dismiss" /> {email}</li>)}
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div className="intro-text" style={{maxWidth: 400}}>
                    <h3>Receive monthly data reports</h3>
                    <p>Add emails to receive reports at the end of the month showing your rating badge analytics.</p>
                </div>
            </div>

            {settings?.agency === true && (
                <React.Fragment>
                    <h2 className="section-title-large">Settings for agency</h2>
                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Sender name</th>
                                <td>
                                    <input onInput={(e) => update_agency('sender_name', e.target.value)} type="text" defaultValue={agency_settings?.sender_name} />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Sender email</th>
                                <td>
                                    <input onInput={(e) => update_agency('sender_email', e.target.value)} type="email" defaultValue={agency_settings?.sender_email} />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Reply to email</th>
                                <td>
                                    <input onInput={(e) => update_agency('reply_to_email', e.target.value)} type="email" defaultValue={agency_settings?.reply_to_email} />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Email logo URL</th>
                                <td>
                                    <input onInput={(e) => update_agency('email_logo', e.target.value)} type="url" defaultValue={agency_settings?.email_logo} />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Email logo URL - Dark</th>
                                <td>
                                    <input onInput={(e) => update_agency('email_logo_dark', e.target.value)} type="url" defaultValue={agency_settings?.email_logo_dark} />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </React.Fragment>
            )}

        </React.Fragment>
    );
};

export default Report;