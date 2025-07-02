import Head from "@/Components/Head.jsx";
import React, {useState} from "react";
import NavBar from "@/Components/NavBar.jsx";
import {Button, Col, Container, Form, Row, FormGroup, Alert} from "reactstrap";
import {useForm, usePage} from "@inertiajs/react";
import RowFormGroup from "@/Components/RowFormGroup.jsx";
import RowFormGroupWithPrefix from "@/Components/RowFormGroupWithPrefix.jsx";
import SubmitButton from "@/Components/SubmitButton.jsx";
import RowFormGroupSelect from "@/Components/RowFormGroupSelect.jsx";

export default ({users}) => {

    const {
        data,
        setData,
        post,
        processing,
        errors,

    } = useForm({
        name: '',
        user_id: users[0].name,
        two_gis_id: '',
    })

    const {routes} = usePage().props;
    const [createAnotherOne, setCreateAnotherOne] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post(`/${routes.backendprefix}/${routes.brunch}` + (createAnotherOne ? `?redirectOnCreation=true` : ''));
    }
    return (<>
        <Head title="Создание филиала"/>
        <NavBar/>
        <Container>
            <Row><Col><h2>Cоздание филиала</h2></Col></Row>
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
                            value="Сохранить"
                            color="primary"
                            outline
                            disabled={processing}
                            onClick={() => setCreateAnotherOne(false)}
                        >Сохранить</Button>
                    </FormGroup>
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
                            value="Сохранить и создать еще"
                            color="primary"
                            outline
                            disabled={processing}
                            onClick={() => setCreateAnotherOne(true)}
                        >Сохранить и создать еще</Button>
                    </FormGroup>
                </Col>
            </Form>
        </Container>
    </>);
}
