<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title>Shell Shockers Map Editor</title>
<link rel="icon" type="image/png" href="http://shellshock.io/favicon.png">

<style>
    * {
        --egg-brown: #532a19;
        font-family: sans-serif;
        font-size: 2.0vh;
        font-weight: normal;
        line-height: 1.2em;
        outline: none;
    }

    html, body {
        padding: 0;
        margin: 0;
        overflow: hidden;
        width: 100%;
        height: 100%;
        color: #fff;
    }

    div, input {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background-clip: padding-box;
    }

    select {
        font-size: 1em;
        background: #fff;
        height: 1.5em;
    }

    h1 { font-size: 1.5em; }
    h2 { font-size: 1.2em; }
    h3 { font-size: 1.1em; }
    small { font-size: 0.9em; }

    h1, h2, h3 {
        margin: 0.2em;
        font-weight: bolder;
    }

    p {
        margin: 0.5em;
        padding: 0;
    }

    .button {
        background: black;
        padding: 0.2em;
        padding-left: 0.6em;
        padding-right: 0.6em;
        border-radius: 0.25em;
        cursor: pointer;
        color: white;
    }

    .toolButton {
        background: black;
        width: 2.8em;
        height: 2.8em;
        border-radius: 0.25em;
        cursor: pointer;
        color: white;
        margin-bottom: 0.25em;
        border: solid;
        border-width: 0.1em;
        border-color: black;
    }

    .toolIcon {
        position: relative;
        top: 50%;
        left: 50%;
        width: 2em;
        height: 2em;
        transform: translate(-50%, -50%);
        pointer-events: none;
        cursor: default;
    }

    input {
        margin: 0;
        padding-left: 0.25em;
        padding-right: 0.25em;
    }

    canvas {
        /*background: var(--egg-brown);*/
        width: 128px;
        height: 128px;
    }

    .break {
        margin: 0.25em;
    }

    .box {
        position: absolute;
        background: white; color: black;
        padding: 1em;
        border-radius: 0.25em;
    }

    .dialog {
        display: none;
        top: 45%; left: 50%;
        transform: translate(-50%, -50%);
    }

    #renderComposting {
        display: none;
        bottom: 1em; right: 1em;
        width: 26em;
    }

    #renderProgress {
        display: none;
        top: 1em;
        left: 1em;
        width: 18em;
    }

    #renderComplete {
        display: none;
        top: 1em;
        left: 1em;
        width: 26em;
    }

    .bgap { margin-bottom: 0.75em; }
    .lgap { margin-left: 0.75em; }
    .rgap { margin-right: 0.75em; }

    .flexCol, .flexRow {
        display: flex;
        flex: 1;
        white-space: nowrap;
    }

    .flexCol {
        align-items: center;
        flex-direction: column;
        text-align: center;
    }

    .flexRow {
        width: 100%;
        justify-content: center;
        flex-direction: row;
    }

    .arrowRight {
        position: absolute;
        width: 0;
        height: 0;
        margin: 0;
        border: none;
        border-top: 1em solid transparent;
        border-bottom: 1em solid transparent;
        border-left: 1.5em solid white;
        cursor: pointer;
    }

    .arrowLeft {
        position: absolute;
        width: 0;
        height: 0;
        margin: 0;
        border: none;
        border-top: 1em solid transparent;
        border-bottom: 1em solid transparent;
        border-right: 1.5em solid white;
        cursor: pointer;
    }

    .statLabel {
        text-align: right;
        font-size: 0.9em;
        white-space: nowrap;
    }

    .outerBar {
        width: 100%;
        padding: 0;
        border: solid;
        border-width: 0.2em;
        border-radius: 0.3em;
        background: var(--egg-brown);
    }

    .innerBar {
        background: #fed;
        width: 100%;
        height: 0.8em;
        border-radius: 0.15em
    }

    .alert {
        position: absolute;
        display: block;
        padding-top: 0.5em;
        padding-bottom: 1em;
        padding-left: 1.5em;
        padding-right: 1.5em;
        background: white;
        text-align: center;
    }

    .dialogPane {
        display: inline-block;
        margin: 1em;
        border-radius: 0.2em;
        vertical-align: top;
    }
</style>

<script>
var skyboxFolders = ["default","moonbase","night","whimsical"];
</script>

<script src="js/mapEdit.js?1729029190"></script>
</head>
<body>
    <canvas id="gameCanvas"></canvas>

    <!-- Main editor window and UI -->
    <div id="editorUi" style="width: 100%; height: 100%">
        <!-- Toolbar -->
        <div id="toolBar" style="
            position: absolute;
            left: 1em;
            top: 10em;
            display: flex;
            flex-direction: column;
        ">
            <!-- Normal -->
            <div id="toolNormal" class="toolButton" onclick="extern.selectTool(this.id)">
                <img src="img/mapEditor/crosshair.webp" style="
                    position: fixed;
                    display: none;
                    left: 50%; top: 50%;
                    transform: translate(-50%, -50%);
                " />

                <img class="toolIcon" src="img/mapEditor/crosshair.webp">
            </div>

            <!-- Eyedropper -->
            <div id="toolDropper" class="toolButton" onclick="extern.selectTool(this.id)">
                <img id="eyeDropper" src="img/mapEditor/eyeDropper.webp" style="
                    display: none;
                    position: fixed;
                    left: 50%; bottom: 50%;
                    transform: translate(-2px, 2px);
                " />
                <img class="toolIcon" src="img/mapEditor/eyeDropper.webp">
                <kbd style="position: relative; top: -1.5em; left: 3em">Shift</kbd>
            </div>

            <!-- Bricklayer -->
            <div id="toolBrick" class="toolButton" onclick="extern.selectTool(this.id)">
                <img src="img/mapEditor/bricks.webp" style="
                    position: fixed;
                    display: none;
                    right: 50%; top: 50%;
                    transform: translate(9px, -6px);
                " />

                <img class="toolIcon" src="img/mapEditor/bricks.webp">
                <kbd style="position: relative; top: -2.2em; left: 3em">Win/<br>Cmd</kbd>
            </div>

            <!-- Paint roller -->
            <div id="toolRoller" class="toolButton" onclick="extern.selectTool(this.id)">
                <img src="img/mapEditor/paintRoller.webp" style="
                    position: fixed;
                    display: none;
                    right: 50%; top: 50%;
                    transform: translate(14px, -10px);
                " />

                <img class="toolIcon" src="img/mapEditor/paintRoller.webp">
                <kbd style="position: relative; top: -1.7em; left: 3em">Alt</kbd>
            </div>

            <!-- Magic wand -->
            <div id="toolWand" class="toolButton" onclick="extern.selectTool(this.id)">
                <img src="img/mapEditor/wand.webp" style="
                    position: fixed;
                    display: none;
                    right: 50%; top: 50%;
                    transform: translate(8px, -8px);
                " />

                <img class="toolIcon" src="img/mapEditor/wand.webp">
                <kbd style="position: relative; top: -1.7em; left: 3em">Ctrl</kbd>
            </div>
        </div>

        <!-- Object menu and palette container -->
        <div style="
            position: absolute;
            display: flex;
            flex-direction: column;
            bottom: 0;
            padding: 0.2em;
            background-color: rgba(0, 0, 0, 0.3);
            left: 50%; transform: translateX(-50%);
        ">
            <!-- Object menu -->
            <div id="objectMenu" style="display: none; overflow: hidden;">
                <!-- Object filters -->
                <div style="display: flex; flex-direction: row; margin-bottom: 0.5em">
                    <div>
                        <select id="themeFilter" oninput="extern.filterObjects()">
                            <option>All themes</option>
                        </select>
                    </div>
                    <div style="padding-left: 0.5em">
                        <select id="colliderFilter" oninput="extern.filterObjects()">
                            <option>All colliders</option>
                        </select>
                    </div>
                    <div style="flex: auto"></div>
                    <span class="button" onclick="extern.toggleObjectMenu()">X</span>
                </div>


                <div id="objects" style="overflow-y: scroll; max-height: 30em"></div>
                <hr>
            </div>

            <!-- Object palette -->
            <div id="palette" style="text-align: center; display: flex; flex-shrink: 0; flex-direction: row;"></div>

        </div>

        <!-- Menu bar -->
        <div style="position: absolute; left: 1em; top: 0.5em;">
            <span class="button" id="new" onclick="extern.onNewPressed()"
                title="Create new, empty map">New</span>

            <input id="filename" type="text">

            <span class="button" id="open" onclick="extern.openMap()"
                title="Open existing map from local file">Open</span>

            <span class="button" id="render" onclick="extern.renderMap()"
                title="Combine meshes and render lightmaps">Render</span>

            <input id="openHelper" type="file" style="position: fixed; top: -100em" onchange="extern.mapOpened(event)">
        </div>

        <div style="position: absolute; left: 1em; top: 2.5em;">
            <span class="button" id="generate" onclick="extern.onGeneratePressed()">Generate</span>

            <span class="button" id="cleanup" onclick="extern.cleanup()"
                title="Eliminates tiles that are not visible">Cleanup</span>

            <span class="button" id="bounds" onclick="extern.createBoundaries()"
                title="Create invisible barriers on edges of map">Bounds</span>
        </div>

        <div style="position: absolute; left: 1em; top: 4.5em;">
            <span class="button" style="cursor: default">
                [R]otation
                <select id="rotation">
                    <option>Smart-ish</option>
                    <option>Random Y</option>
                    <option>Manual</option>
                </select>
            </span>
        </div>
    </div>

    <div style="position: absolute; right: 1em; top: 0.5em; text-align: right">
        <input id="mapName" type="text" size="30" placeholder="Untitled"></input>
        <div style="margin-top: 0.5em">
            <span class="button" id="save" onclick="extern.saveMap()" title="Save map to local file(s)">Save</span>
            <span class="button" id="test" onclick="extern.testMap()" title="Opens map in offline 'game'">Test</span>
            <span class="button" onclick="extern.openDialog('mapSettings')" title="Map Settings">Settings</span>
            <span class="button" onclick="extern.openDialog('help')" title="Help">?</span>

            <div id="surfaceArea" style="margin-top: 0.5em">Surface Area: 0</div>
            <div id="redSpawns" style="margin-top: 0.5em">Red Spawns: 0</div>
            <div id="blueSpawns" style="margin-top: 0.5em">Blue Spawns: 0</div>
        </div>
    </div>

    <!-- Dialog box -->
    <div id="dialog" style="position: absolute; width: 100%; height: 100%; left: 0; top: 0; background: rgba(0, 0, 0, 0.5); white-space: nowrap; display: none;">
        <!-- Help -->
        <div id="help" class="dialog box">
            <h1>Instructions</h1><hr>
            <p>Map creation is entirely cell-based. Think Minecraft!</p>
            <p>Questions about buttons on editor screen? Hover over them for tooltips.</p>

            <h3 style="margin-top: 1em">Navigation</h3>
            <li>Move: W/A/S/D or I/J/K/L keys

            <h3 style="margin-top: 1em">Drawing</h3>
            <li>LMB: Draw, RMB/Backspace: Delete, MMB/Space: Rotate
            <li>Number keys: Select cell category. Press same key to cycle through styles.
            <li>Shift: Hold to activate eye-dropper tool.
            <li>R: Toggle rotation randomization of drawn cells.

            <h3 style="margin-top: 1em">Editing</h3>
            <li>Arrow keys: Move entire map. Hold Shift to move map up/down.
            <li>Ctrl-Z: Undo (only one level; be careful!)

            <div style="text-align: center; margin-top: 1em">
                <span class="button" onclick="extern.closeDialog('help');">OK</span>
            </div>
        </div>

        <!-- New map confirmation -->
        <div id="confirmNew" class="dialog box">
            <h1>New Map</h1><hr>
            <p>This will erase the current map,<br>and cannot be undone. Proceed?</p>

            <div style="text-align: center; margin-top: 1em">
                <span class="button" onclick="extern.newMap()">Yes</span>
                <span class="button" onclick="extern.closeDialog('confirmNew')">No</span>
            </div>
        </div>

        <!-- Generate map confirmation -->
        <div id="confirmGenerate" class="dialog box">
            <h1>Generate Map</h1><hr>
            <p>This will erase the current map,<br>and cannot be undone. Proceed?</p>

            <div style="text-align: center; margin-top: 1em">
                <span class="button" onclick="extern.closeDialog('confirmGenerate'); openDialog('generateMap')">Yes</span>
                <span class="button" onclick="extern.closeDialog('confirmGenerate')">No</span>
            </div>
        </div>

        <!-- Random map generation -->
        <div id="generateMap" class="dialog box">
            <h1>Generate Map</h1><hr>
            <p>Randomly-generates a new map, using currently-selected<br>
            Ground, Block, and Ramp elements as the building blocks.<br>
            If no dimensions are specified, they'll be chosen at random.</p><br>
            <table>
                <tr>
                    <style>td { padding-right: 0.5em; text-align: center; }</style>
                    <td>Width<br><input id="generateWidth" type="text" placeholder="7-50" size="4"></input></td>
                    <td>Depth<br><input id="generateDepth" type="text" placeholder="7-50" size="4"></input></td>
                    <td>Height<br><input id="generateHeight" type="text" placeholder="2-50" size="4"></input></td>
                    <td>Seed<br><input id="generateSeed" type="text" size="20"></input></td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 1em">
                <span class="button" onclick="extern.closeDialog('generateMap'); randomMap()">Generate</span>
                <span class="button" onclick="extern.closeDialog('generateMap')">Cancel</span>
            </div>
        </div>

        <!-- Map settings -->
        <div id="mapSettings" class="dialog box">
            <h1>Map Settings</h1><hr>
            <p>Select game modes, availability, and other parameters for this map.</p>

            <div style="margin-bottom: 1em">
                <div class="dialogPane"><h3>Game Modes</h3><hr>
                    <div id="supportedModes"></div>
                </div>
                <div class="dialogPane">
                    <h3>Availability</h3><hr>
                    <select id="availability">
                        <option value="both">Public & Private</option>
                        <option value="public">Public only</option>
                        <option value="private">Private only</option>
                    </select><p></p>

                    <label><select id="numPlayers"></select>&nbsp;Players</label><p></p>
                </div>
                <div class="dialogPane">
                    <h3>Appearance</h3><hr>
                    <label><input id="sunColor" type="color" onchange="extern.setSunColor(this.value)"></input>&nbsp;Sun Color</label><p></p>
                    <label><input id="ambientColor"type="color" onchange="extern.setAmbientColor(this.value)"></input>&nbsp;Ambient Light</label><p></p>
                    <label><input id="fogColor"type="color" oninput="extern.setFogColor(this.value)"></input>&nbsp;Fog Color</label><p></p>
                    <label>
                        <select id="skyboxes" onchange="extern.setupSkybox(this.value)"></select>
                        &nbsp;Skybox
                    </label>
                </div>
            </div>

            <div style="text-align: center">
                <span class="button" onclick="extern.closeDialog('mapSettings')">OK</span>
            </div>
        </div>
    </div>

    <div id="renderProgress" class="box">
        <span style="display: inline-block">
            <div id="renderPass"></div>
            <progress id="renderBar" max="1" value="0"></progress>
            <div><small id="renderTime"></small></div>
        </span>
        <span style="float: right">
            <button onclick="extern.gi.stop()">Cancel</button>
        </span>
    </div>

    <div id="renderComplete" class="box">
        <span style="display: inline-block">
            Render complete. You may now Export this map for use in-game, complete with lightmap, or Exit to continue editing.
			<p id="renderTotalTime"></p>
        </span>
        <span style="float: right">
            <button onclick="extern.gi.stop()">Exit</button>
        </span>

    </div>

    <!-- Render composting dialog -->

    <div id="renderComposting" class="box">
		<button style="margin-bottom: 1em" onclick="extern.compostDefaults()">Use Defaults</button>
		<div id="compostControls"></div>
    </div>

    <!-- Tooltip -->
    <div id="tooltip" style="position: fixed; left: 0; top: 2em; display: none; background: black; color: white"></div>
<!-- <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"8d5342156f2f9418","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"version":"2024.10.1","token":"b4cd973aeca34b509bef2ed0c9e0b720"}' crossorigin="anonymous"></script> -->
</body>
</html>