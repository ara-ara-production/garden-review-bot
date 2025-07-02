import {Button, FormGroup, Input} from "reactstrap";
import {usePage} from "@inertiajs/react";

export default ({
                    buttonText = '',
                    processing
                }) => {
    const {csrfToken} = usePage().props

    return (
        <FormGroup>
            <Input
                name="_token"
                type="hidden"
                value={csrfToken}
            ></Input>
            <Button
                className="w-100"
                type="submit"
                value={buttonText}
                color="primary"
                outline
                disabled={processing}
            >{buttonText}</Button>
        </FormGroup>
    )
}
