import Head from "@/Components/Head.jsx";
import React, {useState} from "react";
import NavBar from "@/Components/NavBar.jsx";
import {Button, Col, Container, Form, Row, FormGroup, Alert} from "reactstrap";
import {useForm, usePage} from "@inertiajs/react";
import RowFormGroup from "@/Components/RowFormGroup.jsx";
import RowFormGroupWithPrefix from "@/Components/RowFormGroupWithPrefix.jsx";
import SubmitButton from "@/Components/SubmitButton.jsx";
import RowFormGroupSelect from "@/Components/RowFormGroupSelect.jsx";

export default ({values, users}) => {

    const {
        data,
        setData,
        put,
        processing,
        errors,

    } = useForm({
        name: values.name ?? '',
        user_id: values.user_id ?? '',
        two_gis_id: values.two_gis_id ?? '',
        pupr_user_id: values.pupr_user_id ?? '',
        address: values.address ?? ''
    })

    const {routes} = usePage().props;
    const [createAnotherOne, setCreateAnotherOne] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        put(`/${routes.backendprefix}/${routes.brunch}/${values.id}`);
    }
    return (<>
        <Head title="Обновление филиала"/>
        <NavBar/>
        <Container>
            <Row><Col><h2>Обновление филиала</h2></Col></Row>
            <Form className="row" onSubmit={submit}>
                <Col
                    className="
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        "
                >
                    <RowFormGroup
                        label="Наименование *"
                        inputType="text"
                        value={data.name}
                        onChange={e => setData('name', e.target.value)}
                        error={errors.name}
                    />
                    <RowFormGroupSelect
                        label="Управляющий"
                        options={users}
                        value={data.user_id}
                        onChange={e => setData('user_id', e.target.value)}
                        error={errors.user_id}
                    />
                    <RowFormGroupSelect
                        label="Помошник управляющего"
                        options={users}
                        value={data.pupr_user_id}
                        onChange={e => setData('pupr_user_id', e.target.value)}
                        error={errors.pupr_user_id}
                    />
                </Col>

                <Col
                    className="
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        "
                >
                    <RowFormGroup
                        label="id 2Гис филиала"
                        inputType="text"
                        value={data.two_gis_id}
                        onChange={e => setData('two_gis_id', e.target.value)}
                        error={errors.two_gis_id}
                    />
                    <RowFormGroup
                        label="Адрес (используется для api)"
                        inputType="text"
                        value={data.address}
                        onChange={e => setData('address', e.target.value)}
                        error={errors.address}
                    />
                </Col>
                <Col
                    className="
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        "
                >
                    <FormGroup>
                        <Button
                            className="w-100"
                            type="submit"
                            value="Обновить"
                            color="primary"
                            outline
                            disabled={processing}
                        >Обновить</Button>
                    </FormGroup>
                </Col>
            </Form>
        </Container>
    </>);
}
