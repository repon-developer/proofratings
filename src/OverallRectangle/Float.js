import store, { ACTIONS } from "./../Store";
import ColorPicker from "./../Component/ColorPicker";
import Shadow from "./../Component/Shadow";
import Pages from "./../Pages";

import PopupWidget from "../Component/Popup";
import Widgets from "./Widgets";

const { useState, useEffect } = React;

const OverallRectangle_Embed = (props) => {
    const settings = store.getState();

    const [state, setState] = useState(store.getState().overall_rectangle_float);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_rectangle_float));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({
        type: ACTIONS.OVERALL_SAVE,
        payload: {
            name: 'overall_rectangle_float',
            data
        }
    });

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state?.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({ shadow })
    }

    const popup_settings = (typeof state.popup_settings === 'object') ? state.popup_settings : {};
    const handle_popup = (key, value) => {
        popup_settings[key] = value;
        handle_field({ popup_settings } )            
    }

    return (
        <div className="wrapper-page-selection">
            <div className="settings-column">
                <h2 className="section-title-large" style={{ marginTop: 0 }}>Device Visibility</h2>
                <table className="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">Tablet Visibility</th>
                            <td>
                                <label>
                                    <input
                                        type="checkbox"
                                        defaultChecked={state?.tablet}
                                        className="checkbox-switch"
                                        onChange={() => handle_field({ tablet: !state?.tablet })}
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
                                        defaultChecked={state?.mobile}
                                        className="checkbox-switch"
                                        onChange={() => handle_field({ mobile: !state?.mobile })}
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
                                        defaultChecked={state?.close_button}
                                        className="checkbox-switch"
                                        onChange={() => handle_field({ close_button: !state?.close_button })}
                                    />
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Position</th>
                            <td>
                                <select defaultValue={state?.position} onChange={(e) => handle_field({ position: e.target.value })}>
                                    <option value="left">Left</option>
                                    <option value="right">Right</option>
                                </select>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <h2 className="section-title-large">Color Selection</h2>

                <table className="form-table">
                    <tbody>
                        <Widgets {...state} shadow={shadow} />

                        <tr>
                            <th scope="row">Star Color</th>
                            <td><ColorPicker color={state?.star_color} onUpdate={(star_color) => handle_field({ star_color })} /></td>
                        </tr>

                        <tr>
                            <th scope="row">Rating Color</th>
                            <td><ColorPicker color={state?.rating_color} onUpdate={(rating_color) => handle_field({ rating_color })} /></td>
                        </tr>

                        <Shadow shadow={shadow} onUpdate={handleShadow} />

                        <tr>
                            <th scope="row">Background Color</th>
                            <td><ColorPicker color={state?.background_color} onUpdate={(background_color) => handle_field({ background_color })} /></td>
                        </tr>
                        <tr>
                            <th scope="row">Review Text Color</th>
                            <td><ColorPicker color={state?.review_text_color} onUpdate={(review_text_color) => handle_field({ review_text_color })} /></td>
                        </tr>
                        <tr>
                            <th scope="row">Review Background Color</th>
                            <td><ColorPicker color={state?.review_background} onUpdate={(review_background) => handle_field({ review_background })} /></td>
                        </tr>
                    </tbody>
                </table>

                <PopupWidget settings={popup_settings} onUpdate={handle_popup} />
            </div>

            <div className="column-page-selection">
                <h2 className="section-title-large" style={{ marginTop: 0 }}>Page(s) to Show on</h2>
                <Pages onUpdate={handle_field} on_pages={state?.on_pages} />
            </div>
        </div>
    );
};

export default OverallRectangle_Embed;
