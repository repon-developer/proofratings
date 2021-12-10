import store, { ACTIONS } from "./Store";
import ColorPicker from "./ColorPicker";

import Button from "./Button";

const { useState, useEffect } = React;

const CTABanner = () => {

    const [state, setState] = useState(store.getState().overall_cta_banner);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_cta_banner));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_CTA_BANNER, payload: data });

    const handle_button = (name, value) => {
        let button1 = typeof state.button1 === 'object' ? state.button1 : {};        
        button1[name] = value;
        handle_field({button1})
    }

    const handle_button2 = (name, value) => {
        let button2 = typeof state.button2 === 'object' ? state.button2 : {};        
        button2[name] = value;
        handle_field({button2})
    }
    
    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    
                    <tr>
                        <th scope="row">Tablet Visibility</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state.tablet}
                                    className="checkbox-switch"
                                    onChange={() => handle_field({tablet: !state.tablet})}
                                />

                                Show/Hide on tablet
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Mobile Visibility</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state.mobile}
                                    className="checkbox-switch"
                                    onChange={() => handle_field({mobile: !state.mobile})}
                                />
                                Show/Hide on mobile
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Close option</th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state.close_button}
                                    className="checkbox-switch"
                                    onChange={() => handle_field({close_button: !state.close_button})}
                                />
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <td style={{ paddingLeft: 0 }} colSpan={2}>
                            <label>
                                <input
                                    type="checkbox"
                                    className="checkbox-switch"
                                    defaultChecked={state.customize}
                                    onChange={() => handle_field({customize: !state.customize})}
                                /> Customize
                            </label>
                        </td>
                    </tr>

                    {state.customize && (
                        <React.Fragment>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td><ColorPicker onUpdate={(star_color) => handle_field({star_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Top Shadow</th>
                                <td>
                                    <label>
                                        <input
                                            type="checkbox"
                                            defaultChecked={state.shadow}
                                            className="checkbox-switch"
                                            onChange={() => handle_field({shadow: !state.shadow})}
                                        />
                                    </label>
                                </td>
                            </tr>
                        
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Rating Text Color</th>
                                <td><ColorPicker onUpdate={(rating_text_color) => handle_field({rating_text_color})} /></td>
                            </tr>
                            
                            <tr>
                                <th scope="row">Review Rating Background Color</th>
                                <td><ColorPicker onUpdate={(review_rating_background_color) => handle_field({review_rating_background_color})} /></td>
                            </tr>

                            <tr>
                                <th scope="row">Number of Review Text Color</th>
                                <td><ColorPicker onUpdate={(number_review_text_color) => handle_field({number_review_text_color})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

                </tbody>
            </table>

            <h2 style={{fontSize: 25}}>Call-to-action Button</h2>
            <table className="form-table">
                <caption>First Button</caption>
                <tbody>                    
                    <Button key={'button1'} onUpdate={handle_button} {...state.button1}  />
                </tbody>
            </table>

            <div className="gap-30" />
            <table className="form-table">
                <caption>Second Button</caption>
                <tbody>                    
                    <tr>
                        <td colSpan={2} style={{paddingLeft: 0}}>
                            <label>
                                <input
                                    type="checkbox"
                                    defaultChecked={state.button2}
                                    className="checkbox-switch"
                                    onChange={() => handle_button2('show', !state.button2?.show)}
                                /> Second Button
                            </label>
                        </td>
                    </tr>

                    {state.button2?.show && <Button key={'button2'} onUpdate={handle_button2} {...state.button2}  />}
                </tbody>
            </table>

            <table id="floating-badge-pages" className="form-table" style={{}}>
                <caption>Page to show on</caption>
                <tbody>
                    <tr>
                        <th scope="row">Privacy Policy</th>
                        <td>
                            <input name="proofratings_overall_ratings_rectangle[pages][3]" defaultValue="no" type="hidden" /><label><input className="checkbox-switch" name="proofratings_overall_ratings_rectangle[pages][3]" defaultValue="yes" defaultChecked type="checkbox" /></label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Sample Page</th>
                        <td>
                            <input name="proofratings_overall_ratings_rectangle[pages][2]" defaultValue="no" type="hidden" /><label><input className="checkbox-switch" name="proofratings_overall_ratings_rectangle[pages][2]" defaultValue="yes" defaultChecked type="checkbox" /></label>		
                        </td>
                    </tr>
                </tbody>
            </table>

        </React.Fragment>
    );
};

export default CTABanner;
