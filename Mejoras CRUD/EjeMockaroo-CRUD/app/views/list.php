<form>
    <button type="submit" name="orden" value="Nuevo"> Cliente Nuevo</button>
    <br>
</form>
<br>
<form>

    <table>
        <tr>
            <th>
                <p>id</p>
                <p>
                    <button type="submit" name="sort" value="id.asc">↑</button>
                    <button type="submit" name="sort" value="id.desc">↓</button>
                </p>
            </th>
            <th>
                <p>first_name</p>
                <p>
                    <button type="submit" name="sort" value="first_name.asc">↑</button>
                    <button type="submit" name="sort" value="first_name.desc">↓</button>
                </p>
            </th>
            <th>
                <p>email</p>
                <p>
                    <button type="submit" name="sort" value="email.asc">↑</button>
                    <button type="submit" name="sort" value="email.desc">↓</button>
                </p>
            </th>
            <th>
                <p>gender</p>
                <p>
                    <button type="submit" name="sort" value="gender.asc">↑</button>
                    <button type="submit" name="sort" value="gender.desc">↓</button>
                </p>
            </th>
            <th>
                <p>ip_address</p>
                <p>
                    <button type="submit" name="sort" value="ip_address.asc">↑</button>
                    <button type="submit" name="sort" value="ip_address.desc">↓</button>
                </p>
            </th>
            <th>
                <p>teléfono</p>
                <p>
                    <button type="submit" name="sort" value="telefono.asc">↑</button>
                    <button type="submit" name="sort" value="telefono.desc">↓</button>
                </p>
            </th>
        </tr>
        <?php foreach ($tvalores
        as $valor): ?>
        <tr>
            <td><?= $valor->id ?> </td>
            <td><?= $valor->first_name ?> </td>
            <td><?= $valor->email ?> </td>
            <td><?= $valor->gender ?> </td>
            <td><?= $valor->ip_address ?> </td>
            <td><?= $valor->telefono ?> </td>
            <td><a href="#" onclick="confirmarBorrar('<?= $valor->first_name ?>',<?= $valor->id ?>);">Borrar</a></td>
            <td><a href="?orden=Modificar&id=<?= $valor->id ?>">Modificar</a></td>
            <td><a href="?orden=Detalles&id=<?= $valor->id ?>">Detalles</a></td>

        <tr>
            <?php endforeach ?>
    </table>

    <br>
    <button type="submit" name="nav" value="Primero"> <<</button>
    <button type="submit" name="nav" value="Anterior"> <</button>
    <button type="submit" name="nav" value="Siguiente"> ></button>
    <button type="submit" name="nav" value="Ultimo"> >></button>
</form>
