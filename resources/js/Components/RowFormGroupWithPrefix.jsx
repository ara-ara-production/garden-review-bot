import {Col, FormFeedback, FormGroup, FormText, Input, InputGroup, InputGroupText, Label} from "reactstrap";

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
                <InputGroup>
                    <InputGroupText>
                        @
                    </InputGroupText>
                    <Input
                        placeholder={placeHolder}
                        type={inputType}
                        invalid={error !== null}
                        value={value}
                        onChange={onChange}
                    />
                </InputGroup>
                {formText ? <FormText>{formText}</FormText> : null}
                {error ? <FormFeedback>{error}</FormFeedback> : null}
            </Col>

        </FormGroup>
    )
}
