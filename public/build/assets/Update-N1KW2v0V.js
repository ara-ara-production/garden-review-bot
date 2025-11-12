import{x,J as g,r as c,j as e}from"./app-BCgYsvxf.js";import{H as h}from"./Head-DDV0gAg0.js";import{N as j}from"./NavBar-B6VoAAdX.js";import{C as b,R as v,a as d,B as y}from"./Alert-Dw2HE3Yl.js";import{F as C,a as f}from"./Label-qjbLoPHY.js";import{R as s,a as l}from"./RowFormGroupSelect-BRFfx76W.js";import"./Progress--6ehr376.js";import"./FormFeedback-BlThk260.js";import"./Input-f1YSh-2X.js";const E=({values:a,users:i})=>{const{data:o,setData:n,put:u,processing:m,errors:t}=x({name:a.name??"",user_id:a.user_id??"",two_gis_id:a.two_gis_id??"",yandex_vendor_id:a.yandex_vendor_id??"",google_map_id:a.google_map_id??"",pupr_user_id:a.pupr_user_id??"",address:a.address??""}),{routes:p}=g().props,[w,N]=c.useState(!1),_=r=>{r.preventDefault(),u(`/${p.backendprefix}/${p.brunch}/${a.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(h,{title:"Обновление филиала"}),e.jsx(j,{}),e.jsxs(b,{children:[e.jsx(v,{children:e.jsx(d,{children:e.jsx("h2",{children:"Обновление филиала"})})}),e.jsxs(C,{className:"row",onSubmit:_,children:[e.jsxs(d,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(s,{label:"Наименование *",inputType:"text",value:o.name,onChange:r=>n("name",r.target.value),error:t.name}),e.jsx(l,{label:"Управляющий",options:i,value:o.user_id,onChange:r=>n("user_id",r.target.value),error:t.user_id}),e.jsx(l,{label:"Помошник управляющего",options:i,value:o.pupr_user_id,onChange:r=>n("pupr_user_id",r.target.value),error:t.pupr_user_id})]}),e.jsxs(d,{className:`
                        border
                        border-primary
                        rounded
                        col-12
                        mb-4
                        pt-4
                        `,children:[e.jsx(s,{label:"id 2Гис филиала",inputType:"text",value:o.two_gis_id,onChange:r=>n("two_gis_id",r.target.value),error:t.two_gis_id}),e.jsx(s,{label:"id Google филиала",inputType:"text",value:o.google_map_id,onChange:r=>n("google_map_id",r.target.value),error:t.google_map_id}),e.jsx(s,{label:"id Yandex.vendor филиала",inputType:"text",value:o.yandex_vendor_id,onChange:r=>n("yandex_vendor_id",r.target.value),error:t.yandex_vendor_id}),e.jsx(s,{label:"Адрес (используется для api)",inputType:"text",value:o.address,onChange:r=>n("address",r.target.value),error:t.address})]}),e.jsx(d,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(f,{children:e.jsx(y,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:m,children:"Обновить"})})})]})]})]})};export{E as default};
