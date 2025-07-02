import {Col, FormFeedback, FormGroup, FormText, Input, Label} from "reactstrap";

export default ({
                    label,
                    inputType,
                    placeHolder,
                    value,
                    onChange,
                    error = null,
                    formText = null,
                }) => {
    return (
        <FormGroup row className="mb-4">
            <Label sm={3}>{label}</Label>
            <Col sm={9}>
                <Input
                    placeholder={placeHolder}
                    type={inputType}
                    invalid={error !== null}
                    value={value}
                    onChange={onChange}
                />
                {formText ? <FormText>{formText}</FormText> : null}
                {error ? <FormFeedback>{error}</FormFeedback> : null}
            </Col>

        </FormGroup>
    )
}
