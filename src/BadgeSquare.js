import ColorPicker from "./ColorPicker";

const BadgeSquare = (props) => {

    const sites_square = Object.assign({}, props.sites_square);

    const handleColor = () => {
        sites_square.logo_color = color;

        if ( typeof props.updateSettings === 'function' ) {
            props.updateSettings({sites_square});
        }
    }

    return (
        <React.Fragment>
            <div>
                <table className="form-table">
                    <tbody>
                        <tr>
                            <th scope="row" style={{ verticalAlign: "middle" }}>
                                Shortcode
                                <p className="description" style={{ fontWeight: "normal" }}>Use shortcode where you want to display review widgets</p>
                            </th>
                            <td>
                                <code className="shortocde-area">[proofratings_widgets style="square"]</code>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <label>
                    <input
                        name="proofratings_badges_square[customize]"
                        className="checkbox-switch checkbox-yesno"
                        defaultValue="yes"
                        type="checkbox"
                    />
                    Customize (this will customize all badges)
                </label>
                <div className="gap-30" />
                <div id="square-badge-customize">
                    echo do_shortcode( '[proofratings_widgets
                    id="proofratings-badge-square"]');
                    <table className="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Logo Color</th>
                                <td><ColorPicker update={handleColor} defaultValue="#000" /></td>
                            </tr>
                            <tr>
                                <th scope="row">Star Color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Text Color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Review count text color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Background Color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Border</th>
                                <td>
                                    <input
                                        className="checkbox-switch"
                                        name="proofratings_badges_square[border]"
                                        defaultValue="yes"
                                        type="checkbox"
                                    />
                                </td>
                            </tr>
                        </tbody>
                        <tbody
                            id="proofratings-badges-sites-square-border-options"
                            style={{ display: "none" }}
                        >
                            <tr>
                                <th scope="row">Border Color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Border Hover Color</th>
                                <td />
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th scope="row">Shadow</th>
                                <td>
                                    <input
                                        className="checkbox-switch"
                                        name="proofratings_badges_square[shadow]"
                                        defaultValue="no"
                                        type="hidden"
                                    />
                                    <input
                                        className="checkbox-switch"
                                        name="proofratings_badges_square[shadow]"
                                        defaultValue="yes"
                                        type="checkbox"
                                    />
                                </td>
                            </tr>
                        </tbody>
                        <tbody
                            id="proofratings-badges-sites-square-shadow-options"
                            style={{ display: "none" }}
                        >
                            <tr>
                                <th scope="row">Shadow Color</th>
                                <td />
                            </tr>
                            <tr>
                                <th scope="row">Shadow Hover Color</th>
                                <td />
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </React.Fragment>
    );
};

export default BadgeSquare;
