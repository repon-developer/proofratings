import { get_proofratings } from "../global";

const { useEffect } = React;

const Pages = (props) => {
    const on_pages = (Array.isArray(props?.on_pages) ? props.on_pages : []).map(item => parseInt(item));

    useEffect(() => {
        if (Array.isArray(props?.on_pages)) {
            return;
        }

        const on_pages = get_proofratings().pages.map(page => page.ID);
        props.onUpdate({ on_pages })
    }, [])

    const check_pages = (id) => {
        const index = on_pages.indexOf(id);
        if (index !== -1) {
            on_pages.splice(index, 1);
        } else {
            on_pages.push(id);
        }

        props.onUpdate({ on_pages })
    }

    return (
        <table className="form-table">
            <tbody>
                {get_proofratings().pages.map(page => (
                    <tr key={page.ID}>
                        <th scope="row">{page.post_title}</th>
                        <td>
                            <label className="label-switch-checkbox">
                                <input className="checkbox-switch" type="checkbox" onChange={() => check_pages(page.ID)} checked={on_pages.includes(page.ID)} />
                                <span>Don't show on page</span>
                                <span>Show on page</span>
                            </label>
                        </td>
                    </tr>
                ))}

            </tbody>
        </table>
    );
};

export default Pages;
