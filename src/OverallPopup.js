import store, { ACTIONS } from "./Store";
import ColorPicker from "./ColorPicker";
import Border from "./Border";
import Shadow from "./Shadow";

const { useState, useEffect } = React;

const OverallPopup = () => {
    const settings = store.getState();

    const [state, setState] = useState(store.getState().overall_popup);

    useEffect(() => {
        const unsubscribe = store.subscribe(() => setState(store.getState().overall_popup));
        return () => unsubscribe();
    }, [])

    const handle_field = (data) => store.dispatch({ type: ACTIONS.OVERALL_POPUP, payload: data });

    const shadow = Object.assign({ shadow: false, color: "", hover: "" }, state.shadow)
    const handleShadow = (name, value) => {
        shadow[name] = value;
        handle_field({shadow})
    }

    console.log(state)

    return (
        <table className="form-table">
            <tbody>
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
                            <th scope="row">Review Text Color</th>
                            <td><ColorPicker onUpdate={(review_text_color) => handle_field({review_text_color})} /></td>
                        </tr>
                    
                        <tr>
                            <th scope="row">Review Background Color</th>
                            <td><ColorPicker onUpdate={(review_text_background) => handle_field({review_text_background})} /></td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Rating Color</th>
                            <td><ColorPicker onUpdate={(rating_color) => handle_field({rating_color})} /></td>
                        </tr>

                        <tr>
                            <th scope="row">View Review Color</th>
                            <td><ColorPicker onUpdate={(view_review_color) => handle_field({view_review_color})} /></td>
                        </tr>
                    </React.Fragment>
                )}

            </tbody>
        </table>
    );
};

export default OverallPopup;
