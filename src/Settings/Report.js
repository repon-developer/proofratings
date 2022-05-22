const { useState, useEffect } = React;

import store, { ACTIONS } from './Store';

const Report = () => {
    const [settings, setSettings] = useState(store.getState())

    const automated_email_report = Object.assign({ active: false, emails: [] }, settings?.automated_email_report);


    useEffect(() => {
        const unsubscribe = store.subscribe(() => setSettings(store.getState()))
        return () => unsubscribe();
    }, [])

    const handle_email = () => {
        automated_email_report.active = !automated_email_report.active;
        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: {automated_email_report} });
    }

    const add_email = (e) => {
        if (e.keyCode !== 13) {
            return;
        }

        const email = e.target.value.trim();
        const check_email = /\S+@\S+\.\S+/.test(email);

        if (check_email === false) {
            alert('Please enter email address and hit enter.');
            return false;
        }

        automated_email_report.emails.push(email)

        console.log(e)

        store.dispatch({ type: ACTIONS.UPDATE_SETTINGS, payload: { automated_email_report } });
    }

    console.log(automated_email_report);

    return (
        <React.Fragment>
            <h2 className="section-title-large">Email Reporting</h2>
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
                            <input onKeyDown={add_email} type="email" />
                            <p style={{ fontStyle: 'italic', fontSize: 13, marginTop: 0 }}>Type email and hit enter</p>

                            <ul id="reporting-email-addresses">
                                {automated_email_report.emails.map((email, i) => <li key={i}><span className="remove dashicons dashicons-dismiss" /> {email}</li>)}
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>

            {settings?.agency === true && (
                <React.Fragment>
                    <h2 className="section-title-large">Settings for agency</h2>
                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Sender name</th>
                                <td>
                                    <input name="agency[sender-name]" type="text" defaultValue="<?php echo esc_attr(@$email_report_settings['reporting-agency']['sender-name']) ?>" />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Sender email</th>
                                <td>
                                    <input name="agency[sender-email]" type="email" defaultValue="<?php echo esc_attr(@$email_report_settings['reporting-agency']['sender-email']) ?>" />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Reply to email</th>
                                <td>
                                    <input name="agency[reply-to-email]" type="email" defaultValue="<?php echo esc_attr(@$email_report_settings['reporting-agency']['reply-to-email']) ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Email logo URL</th>
                                <td>
                                    <input name="agency[email-logo]" type="url" defaultValue="<?php echo esc_attr(@$email_report_settings['reporting-agency']['email-logo']) ?>" />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Email logo URL - Dark</th>
                                <td>
                                    <input name="agency[email-logo-dark]" type="url" defaultValue="<?php echo esc_attr(@$email_report_settings['reporting-agency']['email-logo-dark']) ?>" />
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