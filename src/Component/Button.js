import ColorPicker from "./ColorPicker";

const Button = (props) => {
    return (
        <React.Fragment>

            <tr>
                <th scope="row">Button Text</th>
                <td>
                    <input
                        type="text"
                        defaultValue={props?.text}
                        onChange={(e) => props.onUpdate('text', e.target.value)}
                    />
                </td>
            </tr>
            <tr>
                <th scope="row">Button URL</th>
                <td>
                    <input
                        type="url"
                        style={{ marginRight: 10 }}
                        defaultValue={props?.url}
                        onChange={(e) => props.onUpdate('url', e.target.value)}
                    />

                    <label className="label-switch-checkbox">
                        <input className="checkbox-switch" type="checkbox" defaultChecked={props?.blank} onChange={(e) => props.onUpdate('blank', !props?.blank)} />
                        <span>Keep on same tab</span>
                        <span>Open in new tab</span>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">Button Text Color</th>
                <td><ColorPicker color={props?.textcolor} onUpdate={(textcolor) => props.onUpdate('textcolor', textcolor)} /></td>
            </tr>
            <tr>
                <th scope="row">Button Background Color</th>
                <td><ColorPicker color={props?.background_color} onUpdate={(background_color) => props.onUpdate('background_color', background_color)} /></td>
            </tr>
            <tr>
                <th scope="row">Button Shape</th>
                <td>
                    <label className="label-switch-checkbox">
                        <input className="checkbox-switch" type="checkbox" defaultChecked={props?.rectangle} onChange={() => props.onUpdate('rectangle', !props?.rectangle)} />
                        <span>Round</span>
                        <span>Rectangle</span>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row">Button Border</th>
                <td>
                    <label className="label-switch-checkbox">
                        <input className="checkbox-switch" type="checkbox" defaultChecked={props?.border} onChange={() => props.onUpdate('rectangle', !props?.border)} />
                        <span>Hide Border</span>
                        <span>Show Border</span>
                    </label>
                </td>
            </tr>

            {props?.border &&
                <tr>
                    <th scope="row">Button Border Color</th>
                    <td><ColorPicker color={props?.border_color} onUpdate={(color) => props.onUpdate('border_color', color)} /></td>
                </tr>
            }

            <tr>
                <th scope="row">Button Hover Text Color</th>
                <td><ColorPicker color={props?.hover_textcolor} onUpdate={(color) => props.onUpdate('hover_textcolor', color)} /></td>
            </tr>

            <tr>
                <th scope="row">Button Hover Background Color</th>
                <td><ColorPicker color={props?.hover_background_color} onUpdate={(color) => props.onUpdate('hover_background_color', color)} /></td>
            </tr>

            {props?.border &&
                <tr>
                    <th scope="row">Button Hover Border Color</th>
                    <td><ColorPicker color={props?.hover_border_color} onUpdate={(color) => props.onUpdate('hover_border_color', color)} /></td>
                </tr>
            }
        </React.Fragment>
    );
};

export default Button;
