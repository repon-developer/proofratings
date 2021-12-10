import store, { ACTIONS } from "./Store";
import ColorPicker from "./ColorPicker";
import Border from "./Border";
import Shadow from "./Shadow";

const { useState, useEffect } = React;

const OverallRectangle = () => {
    const settings = store.getState();

    const [state, setState] = useState(store.getState().overall_rectangle);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_rectangle));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_RECTANGLE, payload: data });

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    console.log(state)

    return (
        <React.Fragment>
            <table className="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            Shortcode
                            <p className="description" style={{ fontWeight: "normal" }}>Embed shortcode</p>
                        </th>
                        <td>
                            <code className="shortocde-area">
                                [proofratings_overall_ratings type="rectangle"]
                            </code>
                        </td>
                    </tr>

                    {settings?.badge_display?.overall_rectangle?.float && (
                        <React.Fragment>
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
                                <th scope="row">Position</th>
                                <td>
                                    <select defaultValue={state.position} onChange={(e) => handle_field({position: e.target.value})}>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </td>
                            </tr>
                        </React.Fragment>
                    )}

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
                                <th scope="row">Rating Color</th>
                                <td><ColorPicker onUpdate={(rating_color) => handle_field({rating_color})} /></td>
                            </tr>

                            <Shadow shadow={shadow} onUpdate={handleShadow} />
                        
                            <tr>
                                <th scope="row">Background Color</th>
                                <td><ColorPicker onUpdate={(background_color) => handle_field({background_color})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review Text Color</th>
                                <td><ColorPicker onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                            </tr>
                            <tr>
                                <th scope="row">Review Background Color</th>
                                <td><ColorPicker onUpdate={(review_background) => handle_field({review_background})} /></td>
                            </tr>
                        </React.Fragment>
                    )}

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

export default OverallRectangle;
