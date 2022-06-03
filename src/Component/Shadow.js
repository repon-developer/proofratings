import ColorPicker from "./ColorPicker";

const Shadow = (props) => {
    const { shadow, color, hover } = Object.assign({ shadow: '', color: '', hover: '' }, props?.shadow)

    const update_shadow = (name, value) => props.onUpdate(name, value)

    return (
        <React.Fragment>
            <tr>
                <th scope="row">Shadow</th>
                <td>
                    <label className="label-switch-checkbox">
                        <input className="checkbox-switch" type="checkbox" defaultChecked={shadow} onChange={() => update_shadow('shadow', !shadow)} />
                        <span>No Shadow</span>
                        <span>Add Shadow</span>
                    </label>
                </td>
            </tr>

            {shadow && (
                <React.Fragment>
                    <tr>
                        <th scope="row">Shadow Color</th>
                        <td>
                            <ColorPicker
                                name="color"
                                color={color}
                                onUpdate={(color) => update_shadow('color', color)}
                            />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Shadow Hover Color</th>
                        <td>
                            <ColorPicker
                                name="hover"
                                color={hover}
                                onUpdate={(hover) => update_shadow('hover', hover)}
                            />
                        </td>
                    </tr>
                </React.Fragment>
            )}
        </React.Fragment>
    );
};

export default Shadow;
