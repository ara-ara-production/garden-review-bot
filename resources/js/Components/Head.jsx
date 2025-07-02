import {Head} from "@inertiajs/react";

export default ({title, description}) => {
    return (
        <Head>
            {title
                ? <title>{title}</title>
                :null
            }
            {
                description
                    ? <meta name="description"
                            content="Garden review bot это бот для получения информации о отзывах гостей с разных платформ. Помогает оператвно реагировать на оставленные отзывы."/>
                    : null
            }
        </Head>
    )
}
