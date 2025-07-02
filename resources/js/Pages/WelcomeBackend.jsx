import {Container, Row} from "reactstrap";
import {Head} from '@inertiajs/react'
import NavBar from "@/Components/NavBar.jsx";
import React from "react";


export default () => {
    return (
        <>
            <Head title="Бек"/>
            <NavBar/>
            <Container fluid className="h-100 d-flex align-items-center justify-content-center">
                <Row>
                    <h2>Закрытая веб страница телеграм бота!</h2>
                </Row>
            </Container>
        </>
    )
}
