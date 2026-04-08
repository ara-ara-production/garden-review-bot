import{a as x,u as g,r as c,j as e}from"./app-CbBDZ2-1.js";import{H as h}from"./Head-C5rbOLc6.js";import{N as j}from"./NavBar-GBX9edZl.js";import{C as b,R as y,a as d,B as v}from"./Alert-CqTlS43w.js";import{F as C,a as f}from"./Label-BbD99JWr.js";import{a as s,R as l}from"./RowFormGroupSelect-DGNj5Bbf.js";import"./Progress-BkDbzNHp.js";import"./FormFeedback-_7wxNjiy.js";import"./Input-BEp1MAUa.js";const E=({values:a,users:i})=>{console.log(a);const{data:o,setData:n,put:_,processing:m,errors:t}=x({name:a.name??"",user_id:a.user_id??"",two_gis_id:a.two_gis_id??"",yandex_vendor_id:a.yandex_vendor_id??"",yandex_map_id:a.yandex_map_id??"",google_map_id:a.google_map_id??"",pupr_user_id:a.pupr_user_id??"",address:a.address??""}),{routes:p}=g().props,[w,F]=c.useState(!1),u=r=>{r.preventDefault(),_(`/${p.backendprefix}/${p.brunch}/${a.id}`)};return e.jsxs(e.Fragment,{children:[e.jsx(h,{title:"Обновление филиала"}),e.jsx(j,{}),e.jsxs(b,{children:[e.jsx(y,{children:e.jsx(d,{children:e.jsx("h2",{children:"Обновление филиала"})})}),e.jsxs(C,{className:"row",onSubmit:u,children:[e.jsxs(d,{className:`
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
                        `,children:[e.jsx(s,{label:"id 2Гис филиала",inputType:"text",value:o.two_gis_id,onChange:r=>n("two_gis_id",r.target.value),error:t.two_gis_id}),e.jsx(s,{label:"id Google филиала",inputType:"text",value:o.google_map_id,onChange:r=>n("google_map_id",r.target.value),error:t.google_map_id}),e.jsx(s,{label:"id Yandex.vendor филиала",inputType:"text",value:o.yandex_vendor_id,onChange:r=>n("yandex_vendor_id",r.target.value),error:t.yandex_vendor_id}),e.jsx(s,{label:"id Yandex.Карты филиала",inputType:"text",value:o.yandex_map_id,onChange:r=>n("yandex_map_id",r.target.value),error:t.yandex_map_id}),e.jsx(s,{label:"Адрес (используется для api)",inputType:"text",value:o.address,onChange:r=>n("address",r.target.value),error:t.address})]}),e.jsx(d,{className:`
                        col-4
                        mb-4
                        pl-0
                        pt-4
                        `,children:e.jsx(f,{children:e.jsx(v,{className:"w-100",type:"submit",value:"Обновить",color:"primary",outline:!0,disabled:m,children:"Обновить"})})})]})]})]})};export{E as default};
