import ColorPicker from "./ColorPicker";

const Shadow = (props) => {
    const {shadow, color, hover} = Object.assign({shadow: '', color: '', hover: ''}, props?.shadow)

    const update_border = (name, value) => props.onUpdate(name, value)

    return (
        <React.Fragment>
            <tr>
                <th scope="row">Shadow</th>
                <td>
                    <input
                        type="checkbox"
                        defaultChecked={shadow}
                        onChange={() => update_border('shadow', !shadow)}
                        className="checkbox-switch"
                    />
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
                                onUpdate={(color) => update_border('color', color)}
                            />

                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Shadow Hover Color</th>
                        <td>
                            <ColorPicker
                                name="hover"
                                color={hover}
                                onUpdate={(hover) => update_border('hover', hover)}
                            />
                        </td>
                    </tr>
                </React.Fragment>
            )}
        </React.Fragment>
    );
};

export default Shadow;
